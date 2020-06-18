<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category  module
 * @package   afterpay
 * @author    OXID Professional services
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\DataProvider;

class PaymentDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Testing method getDataObject
     */
    public function testgetPayment()
    {
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['createInvoicePayment','createDebitNotePayment', 'createInstallmentPayment'])
            ->getMock();

        $sut->method('createInvoicePayment')->willReturn(1);
        $sut->method('createDebitNotePayment')->willReturn(2);
        $sut->method('createInstallmentPayment')->willReturn(3);

        $this->assertEquals(1, $sut->getPayment('afterpayinvoice'));
        $this->assertEquals(2, $sut->getPayment('afterpaydebitnote'));
        $this->assertEquals(3, $sut->getPayment('afterpayinstallment'));

        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\PaymentException::class);
        $this->assertEquals(3, $sut->getPayment('foobar'));
    }

    public function testcreateDebitNotePayment()
    {
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class);
        $sutreturn = $sut->createDebitNotePayment(1, 2);
        $this->assertEquals('{"type":"Invoice","directDebit":{"bankAccount":1,"bankCode":2}}', json_encode($sutreturn->exportData()));
    }

    public function testcreateInvoicePayment()
    {
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class);
        $sutreturn = $sut->createInvoicePayment(1, 2);
        $this->assertEquals('{"type":"Invoice"}', json_encode($sutreturn->exportData()));
    }

    public function testcreateInstallmentPayment()
    {
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class);
        $sutreturn = $sut->createInstallmentPayment(1, 2, 3, 4);
        $expected = '{"type":"Installment","directDebit":{"bankAccount":1,"bankCode":2},"installment":{"profileNo":3,"numberOfInstallments":4}}';
        $this->assertEquals($expected, json_encode($sutreturn->exportData()));
    }
}
