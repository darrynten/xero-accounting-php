<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\PaymentTermsTypes;

class PaymentTermsTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testPaymentTermsTypesConstants()
    {
        $this->assertEquals(PaymentTermsTypes::DAYSAFTERBILLDATE, 'DAYSAFTERBILLDATE');
        $this->assertEquals(PaymentTermsTypes::DAYSAFTERBILLMONTH, 'DAYSAFTERBILLMONTH');
        $this->assertEquals(PaymentTermsTypes::OFCURRENTMONTH, 'OFCURRENTMONTH');
        $this->assertEquals(PaymentTermsTypes::OFFOLLOWINGMONTH, 'OFFOLLOWINGMONTH');
    }

    public function testPaymentTermsTypesDescriptions()
    {
        $paymentTermsTypes = new PaymentTermsTypes;
        $expected = [
            PaymentTermsTypes::DAYSAFTERBILLDATE => 'Day(s) after bill date',
            PaymentTermsTypes::DAYSAFTERBILLMONTH => 'Day(s) after bill month',
            PaymentTermsTypes::OFCURRENTMONTH => 'Of the current month',
            PaymentTermsTypes::OFFOLLOWINGMONTH => 'Of the following month',
        ];

        $this->assertEquals($expected, $paymentTermsTypes->descriptions);
    }
}