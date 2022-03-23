<?php

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class RefundItemDataProviderTest extends UnitTestCase
{
    /**
     * @throws StandardException
     */
    public function testgetRefundDataFromVatSplittedRefundsException()
    {
        $this->expectException(StandardException::class);

        $items = [];

        /** @var RefundItemDataProvider|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(RefundItemDataProvider::class)
                    ->disableOriginalConstructor()
                    ->setMethods(['getRefundItemList'])
                    ->getMock();
        $sut->method('getRefundItemList')->willReturn($items);

        $sut->getRefundDataFromVatSplittedRefunds(123, $items);
    }

    /**
     * @throws StandardException
     */
    public function testgetRefundDataFromVatSplittedRefundsOk()
    {
        $items = [1, 2, 3];

        /** @var RefundItemDataProvider|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(RefundItemDataProvider::class)
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

        /** @var RefundItemDataProvider|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(RefundItemDataProvider::class)
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
        $sut = oxNew(RefundItemDataProvider::class);
        $sutreturn = $sut->getRefundItem($items);
        $expected = '{"grossUnitPrice":99.99,"netUnitPrice":0,"vatAmount":99.99,"vatPercent":19}';

        // https://stackoverflow.com/a/43056278 Fix PHP7 precision issue on json_encode
        ini_set('serialize_precision', -1);
        $this->assertEquals($expected, json_encode($sutreturn->exportData(), JSON_PRESERVE_ZERO_FRACTION));
    }

    public function testgetRefundItemError()
    {
        $items = ['vatPercent' => 19, 'grossUnitPrice' => 0];
        $sut = oxNew(RefundItemDataProvider::class);
        $refundItem = $sut->getRefundItem($items);
        $this->assertNull($refundItem);
    }
}
