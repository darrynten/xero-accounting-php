<?php

namespace DarrynTen\Xero\Tests\SageOne;

use DarrynTen\Xero\Exception\LibraryException;

class LibraryExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testMethodNotImplemented()
    {
        $this->expectException(LibraryException::class);
        $this->expectExceptionMessage('Error, "/path/to/method::here()" Method not yet implemented. This still needs to be added, please consider contributing to the project.');
        $this->expectExceptionCode(20501);

        throw new LibraryException(20501, '/path/to/method::here()');
    }
}
