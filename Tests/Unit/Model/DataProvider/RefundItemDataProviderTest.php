<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

class RefundItemDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testgetRefundDataFromVatSplittedRefundsException()
    {
        $this->setExpectedException(\OxidEsales\Eshop\Core\Exception\StandardException::class);

        $items = [];

        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRefundItemList'])
            ->getMock();
        $sut->method('getRefundItemList')->willReturn($items);

        $sut->getRefundDataFromVatSplittedRefunds(123, $items);
    }

    public function testgetRefundDataFromVatSplittedRefundsOk()
    {
        $items = [1, 2, 3];

        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)
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

        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)
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
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class);
        $sutreturn = $sut->getRefundItem($items);
        $expected = '{"grossUnitPrice":99.99,"netUnitPrice":0,"vatAmount":99.99,"vatPercent":19}';
        $this->assertEquals($expected, json_encode($sutreturn->exportData()));
    }

    public function testgetRefundItemError()
    {
        $items = ['vatPercent' => 19, 'grossUnitPrice' => 0];
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class);
        $sutreturn = $sut->getRefundItem($items);
        $this->assertNull($sutreturn);
    }
}
