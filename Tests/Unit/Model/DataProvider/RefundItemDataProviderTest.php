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

class RefundItemDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testgetRefundDataFromVatSplittedRefundsException()
    {
        $this->setExpectedException(\OxidEsales\Eshop\Core\Exception\StandardException::class);

        $items = [];

        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRefundItemList'])
            ->getMock();
        $sut->method('getRefundItemList')->willReturn($items);

        $sut->getRefundDataFromVatSplittedRefunds(123, $items);
    }

    public function testgetRefundDataFromVatSplittedRefundsOk()
    {
        $items = [1, 2, 3];

        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRefundItemList'])
            ->getMock();
        $sut->method('getRefundItemList')->willReturn($items);

        $sutreturn = $sut->getRefundDataFromVatSplittedRefunds(123, $items);

        $expected = '{"orderItems":[1,2,3],"captureNumber":123}';
        $this->assertEquals($expected, json_encode($sutreturn->exportData()));
    }

    public function testgetRefundItemList()
    {

        $items = [['vatPercent' => 19, 'grossUnitPrice' => 99.99]];

        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRefundItem'])
            ->getMock();

        $sut->method('getRefundItem')->willReturn(1);

        $sutreturn = $sut->getRefundItemList($items);

        $expected = '[1]';
        $this->assertEquals($expected, json_encode($sutreturn));
    }

    public function testgetRefundItemOk()
    {
        $items = ['vatPercent' => 19, 'grossUnitPrice' => 99.99];
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class);
        $sutreturn = $sut->getRefundItem($items);
        $expected = '{"grossUnitPrice":99.99,"netUnitPrice":0,"vatAmount":99.99,"vatPercent":19}';
        $this->assertEquals($expected, json_encode($sutreturn->exportData()));
    }

    public function testgetRefundItemError()
    {
        $items = ['vatPercent' => 19, 'grossUnitPrice' => 0];
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class);
        $sutreturn = $sut->getRefundItem($items);
        $this->assertNull($sutreturn);
    }
}
