<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\PaymentTypes;

class PaymentTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testPaymentTypesConstants()
    {
        $this->assertEquals(PaymentTypes::ACCRECPAYMENT, 'ACCRECPAYMENT');
        $this->assertEquals(PaymentTypes::ACCPAYPAYMENT, 'ACCPAYPAYMENT');
        $this->assertEquals(PaymentTypes::ARCREDITPAYMENT, 'ARCREDITPAYMENT');
        $this->assertEquals(PaymentTypes::APCREDITPAYMENT, 'APCREDITPAYMENT');
        $this->assertEquals(PaymentTypes::ARPREPAYMENTPAYMENT, 'ARPREPAYMENTPAYMENT');
        $this->assertEquals(PaymentTypes::APPREPAYMENTPAYMENT, 'APPREPAYMENTPAYMENT');
        $this->assertEquals(PaymentTypes::APOVERPAYMENTPAYMENT, 'APOVERPAYMENTPAYMENT');
        $this->assertEquals(PaymentTypes::AROVERPAYMENTPAYMENT, 'AROVERPAYMENTPAYMENT');
    }

    public function testPaymentTypesDescriptions()
    {
        $paymentTypes = new PaymentTypes;
        $expected = [
            PaymentTypes::ACCRECPAYMENT => 'Accounts Receivable Payment',
            PaymentTypes::ACCPAYPAYMENT => 'Accounts Payable Payment',
            PaymentTypes::ARCREDITPAYMENT => 'Accounts Receivable Credit Payment (Refund)',
            PaymentTypes::APCREDITPAYMENT => 'Accounts Payable Credit Payment (Refund)',
            PaymentTypes::ARPREPAYMENTPAYMENT => 'Accounts Receivable Prepayment Payment (Refund)',
            PaymentTypes::APPREPAYMENTPAYMENT => 'Accounts Payable Prepayment Payment (Refund)',
            PaymentTypes::APOVERPAYMENTPAYMENT => 'Accounts Payable Overpayment Payment (Refund)',
            PaymentTypes::AROVERPAYMENTPAYMENT => 'Accounts Receivable Overpayment Payment (Refund)',
        ];

        $this->assertEquals($expected, $paymentTypes->descriptions);
    }
}
