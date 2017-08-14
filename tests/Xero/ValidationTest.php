<?php

namespace DarrynTen\Xero\Tests\Xero;

use DarrynTen\Xero\Exception\ValidationException;
use DarrynTen\Xero\Validation;
use DarrynTen\Xero\Xero;
use DarrynTen\Xero\Request\RequestHandler;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    use Validation;

    /**
     * The object under test.
     *
     * @var object
     */
    private $traitObject;

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->traitObject = $this->createObjectForTrait();
    }

    /**
     * *Creation Method* to create an object for the trait under test.
     *
     * @return object The newly created object.
     */
    private function createObjectForTrait()
    {
        return $this->getObjectForTrait(Validation::class);
    }

    public function testRegexpException()
    {
        $value = 'bar';
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(sprintf('value %s failed to validate', $value));
        $this->expectExceptionCode(ValidationException::STRING_REGEX_MISMATCH);

        $this->traitObject->validateRegex($value, '/^foo$/');
    }

    public function testRegexpSuccess()
    {
        $this->traitObject->validateRegex('bar', '/^bar$/');
    }

    public function testRangeExceptionInteger()
    {
        $value = 10;
        $min = 11;
        $max = 12;
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf(
                'value %s out of min(%s) max(%s)',
                $value,
                $min,
                $max
            )
        );
        $this->expectExceptionCode(ValidationException::INTEGER_OUT_OF_RANGE);

        $this->traitObject->validateRange($value, $min, $max);
    }

    public function testRangeSuccessInteger()
    {
        $this->traitObject->validateRange(10, 9, 11);
    }

    public function testRangeExceptionString()
    {
        $value = 'some string out of range';
        $min = 11;
        $max = 12;
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf(
                'value %s out of min(%s) max(%s)',
                $value,
                $min,
                $max
            )
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);

        $this->traitObject->validateRange($value, $min, $max);
    }

    public function testRangeSuccessString()
    {
        $this->traitObject->validateRange('some_string', 0, 20);
    }

    public function testRangeRangeExceptionUnknownType()
    {
        $value = 10.1;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf(
                'value %s is type %s',
                $value,
                gettype($value)
            )
        );
        $this->expectExceptionCode(ValidationException::VALIDATION_TYPE_ERROR);

        $this->traitObject->validateRange($value, 0, 1);
    }

    public function testValidPrimitiveException()
    {
        $this->assertTrue(
            $this->traitObject->isValidPrimitive('some string', 'string')
        );
        $this->assertFalse(
            $this->traitObject->isValidPrimitive('some string', 'integer')
        );
        $this->assertfalse(
            $this->traitObject->isValidPrimitive(1.02, 'float')
        );
    }

    public function testIsValidPrimitive()
    {
        $this->assertTrue($this->isValidPrimitive(5, 'integer'));
        $this->assertEquals(false, $this->isValidPrimitive(5, 'string'));
    }

    public function testBadRegexException()
    {
        $this->assertEquals(null, $this->validateRegex('2', '~[0-9]~'));
        $this->assertException(
            ValidationException::class,
            'value fergus failed to validate',
            ValidationException::STRING_REGEX_MISMATCH
        );
        $this->validateRegex('fergus', '~[0-9]~');
    }

    public function testValidateIntegerException()
    {
        $this->assertEquals(null, $this->validateRange(10, 5, 15));
        $this->assertException(
            ValidationException::class,
            'Validation error value 20 out of min(5) max(15) Integer value is out of range',
            ValidationException::INTEGER_OUT_OF_RANGE
        );
        $this->validateRange(20, 5, 15);
    }

    public function testValidateStringException()
    {
        $this->assertEquals(null, $this->validateRange('asd', 1, 6));
        $this->assertException(
            ValidationException::class,
            'Validation error value AAaaaaaa out of min(5) max(6) String length is out of range',
            ValidationException::STRING_LENGTH_OUT_OF_RANGE
        );
        $this->validateRange('AAaaaaaa', 5, 6);
    }

    public function testValidationTypeException()
    {
        $this->assertException(
            ValidationException::class,
            'Validation error value 7.2336 is type double Validation type is invalid',
            ValidationException::VALIDATION_TYPE_ERROR
        );
        $this->validateRange(7.2336, 5, 15);
    }

    //above tests reach 100% coverage, robustness tests below

    public function testValidateNull()
    {
        $this->assertException(
            ValidationException::class,
            'Validation error value  is type NULL Validation type is invalid',
            ValidationException::VALIDATION_TYPE_ERROR
        );
        $this->validateRange(null, 5, 15);
    }

    public function testValidateNullCharacter()
    {
        //These strings need the double inverted comma for the null character to register properly
        $this->assertException(
            ValidationException::class,
            "Validation error value \0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0asdhd out of min(5) max(15) String length is out of range",
            ValidationException::STRING_LENGTH_OUT_OF_RANGE
        );
        $this->validateRange("\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0asdhd", 5, 15);
    }

    public function testValidateNullType()
    {
        //Should this be valid?
        $this->assertTrue($this->isValidPrimitive("\0foo", 'string'));
    }

    public function testValidateDifferentNullType()
    {
        //Should this be valid?
        $this->assertTrue($this->isValidPrimitive("\x00foo", 'string'));
    }

    public function testValidateURLHexEncodedType()
    {
        //URLEncoding
        //Should this be valid?
        $this->assertTrue($this->isValidPrimitive("foo%60%7ebar", 'string'));
        //Should this be valid?
        $this->assertTrue($this->isValidPrimitive(urldecode("foo%60%7ebar"), 'string'));

        //HexEncoding
        $this->assertTrue($this->isValidPrimitive(bin2hex("foobar"), 'string'));
        $this->assertException(
            ValidationException::class,
            "Validation error value 666f6f0a626172 out of min(5) max(10) String length is out of range",
            ValidationException::STRING_LENGTH_OUT_OF_RANGE
        );
        $this->validateRange(bin2hex("foo\nbar"), 5, 10);
    }

    public function testValidateBaseSixtyFourEncodedType()
    {
        //b64 encoding tests
        $this->assertTrue($this->isValidPrimitive(base64_encode("foobar"), 'string'));
        $this->validateRange(base64_encode("foobar"), 5, 15);
        $this->assertException(
            ValidationException::class,
            'Validation error value Zm9vYmFyIGJhcmZvbyAKIHpvbw== out of min(5) max(15) String length is out of range',
            ValidationException::STRING_LENGTH_OUT_OF_RANGE
        );
        $this->validateRange(base64_encode("foobar barfoo \n zoo"), 5, 15);
    }

    public function testEmojiValidation()
    {
        //is this valid?
        $this->validateRange(json_decode('"\uD83D\uDE00"'), 0, 8);
        //Emoji is one character, can this lead to problems?
        $this->validateRange(json_decode('"\uD83D\uDE00"'), 0, 2);
        $this->assertException(
            ValidationException::class,
            "Validation error value 😀😀😀 out of min(1) max(2) String length is out of range",
            ValidationException::STRING_LENGTH_OUT_OF_RANGE
        );
        $this->validateRange(
            json_decode('"\uD83D\uDE00"')
            . json_decode('"\uD83D\uDE00"') . json_decode('"\uD83D\uDE00"'),
            1,
            2
        );
    }

    public function testLargeIntValidation()
    {
        $this->assertException(
            ValidationException::class,
            "Validation error value 1.0E+25 is type double Validation type is invalid",
            ValidationException::VALIDATION_TYPE_ERROR
        );
        $this->validateRange(9999999999999999999999999, 1, 8);
    }

    public function testOutlierChars()
    {
        $this->validateRange(0x00, 0, 8);
        $this->assertException(
            ValidationException::class,
            "Validation error value 255 out of min(0) max(8) Integer value is out of range",
            ValidationException::INTEGER_OUT_OF_RANGE
        );
        $this->validateRange(0xFF, 0, 8);
    }

    private function assertException($class, String $message, int $code)
    {
        $this->expectException($class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($code);
    }
}
