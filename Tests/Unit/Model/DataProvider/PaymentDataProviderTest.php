<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider;
use Arvato\AfterpayModule\Core\Exception\PaymentException;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class PaymentDataProviderTest extends UnitTestCase
{

    /**
     * Testing method getDataObject
     *
     * @throws PaymentException
     */
    public function testgetPayment()
    {
        /** @var PaymentDataProvider|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentDataProvider::class)
                    ->disableOriginalConstructor()
                    ->setMethods(['createInvoicePayment', 'createDebitNotePayment', 'createInstallmentPayment'])
                    ->getMock();

        $sut->method('createInvoicePayment')->willReturn(1);
        $sut->method('createDebitNotePayment')->willReturn(2);
        $sut->method('createInstallmentPayment')->willReturn(3);

        $this->assertEquals(1, $sut->getPayment('afterpayinvoice'));
        $this->assertEquals(2, $sut->getPayment('afterpaydebitnote'));
        $this->assertEquals(3, $sut->getPayment('afterpayinstallment'));

        $this->setExpectedException(PaymentException::class);
        $this->assertEquals(3, $sut->getPayment('foobar'));
    }

    public function testcreateDebitNotePayment()
    {
        $sut = oxNew(PaymentDataProvider::class);
        $payment = $sut->createDebitNotePayment(1, 2);
        $this->assertEquals('{"type":"Invoice","directDebit":{"bankAccount":1,"bankCode":2}}', json_encode($payment->exportData()));
    }

    public function testcreateInvoicePayment()
    {
        $sut = oxNew(PaymentDataProvider::class);
        $payment = $sut->createInvoicePayment();
        $this->assertEquals('{"type":"Invoice"}', json_encode($payment->exportData()));
    }

    public function testcreateInstallmentPayment()
    {
        $sut = oxNew(PaymentDataProvider::class);
        $payment = $sut->createInstallmentPayment(1, 2, 3, 4);
        $expected = '{"type":"Installment","directDebit":{"bankAccount":1,"bankCode":2},"installment":{"profileNo":3,"numberOfInstallments":4}}';
        $this->assertEquals($expected, json_encode($payment->exportData()));
    }
}
