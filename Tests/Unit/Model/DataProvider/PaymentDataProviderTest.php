<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

class PaymentDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Testing method getDataObject
     */
    public function testgetPayment()
    {
        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['createInvoicePayment','createDebitNotePayment', 'createInstallmentPayment'])
            ->getMock();

        $sut->method('createInvoicePayment')->willReturn(1);
        $sut->method('createDebitNotePayment')->willReturn(2);
        $sut->method('createInstallmentPayment')->willReturn(3);

        $this->assertEquals(1, $sut->getPayment('afterpayinvoice'));
        $this->assertEquals(2, $sut->getPayment('afterpaydebitnote'));
        $this->assertEquals(3, $sut->getPayment('afterpayinstallment'));

        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\PaymentException::class);
        $this->assertEquals(3, $sut->getPayment('foobar'));
    }

    public function testcreateDebitNotePayment()
    {
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class);
        $sutreturn = $sut->createDebitNotePayment(1, 2);
        $this->assertEquals('{"type":"Invoice","directDebit":{"bankAccount":1,"bankCode":2}}', json_encode($sutreturn->exportData()));
    }

    public function testcreateInvoicePayment()
    {
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class);
        $sutreturn = $sut->createInvoicePayment(1, 2);
        $this->assertEquals('{"type":"Invoice"}', json_encode($sutreturn->exportData()));
    }

    public function testcreateInstallmentPayment()
    {
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class);
        $sutreturn = $sut->createInstallmentPayment(1, 2, 3, 4);
        $expected = '{"type":"Installment","directDebit":{"bankAccount":1,"bankCode":2},"installment":{"profileNo":3,"numberOfInstallments":4}}';
        $this->assertEquals($expected, json_encode($sutreturn->exportData()));
    }
}
