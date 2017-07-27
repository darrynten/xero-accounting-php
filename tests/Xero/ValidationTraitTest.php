<?php

namespace DarrynTen\Xero\Tests\Xero;

use DarrynTen\Xero\Validation;
use DarrynTen\Xero\Xero;
use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\Xero\Exception\ValidationException;

class ValidationTraitTest extends \PHPUnit_Framework_TestCase
{
    use Validation;

    public function testIsValidPrimitive()
    {
        $this->assertTrue($this->isValidPrimitive(5, 'integer'));
        $this->assertEquals(false, $this->isValidPrimitive(5, 'string'));
    }

    public function testBadRegexException()
    {
        $this->assertEquals(null, $this->validateRegex('2', '~[0-9]~'));
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf('value fergus failed to validate')
        );
        $this->expectExceptionCode(ValidationException::STRING_REGEX_MISMATCH);
        $this->validateRegex('fergus', '~[0-9]~');
    }

    public function testValidateIntegerException()
    {
        $this->assertEquals(null, $this->validateRange(10, 5, 15));
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf('Validation error value 20 out of min(5) max(15) Integer value is out of range')
        );
        $this->expectExceptionCode(ValidationException::INTEGER_OUT_OF_RANGE);
        $this->validateRange(20, 5, 15);
    }

    public function testValidateStringException()
    {
        $this->assertEquals(null, $this->validateRange('asd', 1, 6));
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf('Validation error value AAaaaaaa out of min(5) max(6) String length is out of range')
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);
        $this->validateRange('AAaaaaaa', 5, 6);
    }

    public function testValidationTypeException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf('Validation error value 7.2336 is type double Validation type is invalid')
        );
        $this->expectExceptionCode(ValidationException::VALIDATION_TYPE_ERROR);
        $this->validateRange(7.2336, 5, 15);
    }

    //above tests reach 100% coverage, robustness tests below

    public function testValidateNull()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf('Validation error value  is type NULL Validation type is invalid')
        );
        $this->expectExceptionCode(ValidationException::VALIDATION_TYPE_ERROR);
        $this->validateRange(null, 5, 15);
    }

    public function testValidateNullCharacter()
    {
        //These strings need the double inverted comma for the null character to register properly
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf("Validation error value \0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0asdhd out of min(5) max(15) String length is out of range")
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);
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
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf("Validation error value 666f6f0a626172 out of min(5) max(10) String length is out of range")
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);
        $this->validateRange(bin2hex("foo\nbar"), 5, 10);
    }

    public function testValidateBaseSixtyFourEncodedType()
    {
        //b64 encoding tests
        $this->assertTrue($this->isValidPrimitive(base64_encode("foobar"), 'string'));
        $this->validateRange(base64_encode("foobar"), 5, 15);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf("Validation error value Zm9vYmFyIGJhcmZvbyAKIHpvbw== out of min(5) max(15) String length is out of range")
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);
        $this->validateRange(base64_encode("foobar barfoo \n zoo"), 5, 15);
    }

    public function testEmojiValidation()
    {
        //is this valid?
        $this->validateRange(json_decode('"\uD83D\uDE00"'), 1, 8);
        //Emoji is one character, can this lead to problems?
        $this->validateRange(json_decode('"\uD83D\uDE00"'), 1, 2);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf("Validation error value 😀😀😀 out of min(1) max(2) String length is out of range")
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);
        $this->validateRange(json_decode('"\uD83D\uDE00"')
            . json_decode('"\uD83D\uDE00"') . json_decode('"\uD83D\uDE00"'),
            1, 2);
    }

    public function testLargeIntValidation(){
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf("Validation error value 1.0E+25 is type double Validation type is invalid")
        );
        $this->expectExceptionCode(ValidationException::VALIDATION_TYPE_ERROR);
        $this->validateRange(9999999999999999999999999, 1, 8);
    }

    public function testOutlierChars(){
        $this->validateRange(0x00, 0, 8);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf("Validation error value 255 out of min(0) max(8) Integer value is out of range")
        );
        $this->expectExceptionCode(ValidationException::INTEGER_OUT_OF_RANGE);
        $this->validateRange(0xFF, 0, 8);

    }
}