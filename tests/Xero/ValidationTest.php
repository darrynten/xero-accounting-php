<?php

namespace DarrynTen\Xero\Tests\Xero;


use DarrynTen\Xero\Exception\ValidationException;
use DarrynTen\Xero\Validation;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
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
}
