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
}