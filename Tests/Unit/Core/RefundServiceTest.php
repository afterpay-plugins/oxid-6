<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use Arvato\AfterpayModule\Core\Exception\CurlException;
use Arvato\AfterpayModule\Core\RefundService;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class RefundServiceTest extends UnitTestCase
{

    public function testConstruct()
    {
        $this->assertInstanceOf(
            RefundService::class,
            oxNew(RefundService::class, oxNew(Order::class))
        );
    }

    /**
     * @throws CurlException
     */
    public function testRefundException()
    {
        $this->setExpectedException(CurlException::class);
        $sut = oxNew(RefundService::class, oxNew(Order::class));
        $sut->refund(null, 'SomeApiKey');
    }

    /**
     * @throws CurlException
     */
    public function testRefundOk()
    {
        $oxOrder = oxNew(Order::class);
        $afterpayOrder = oxNew(AfterpayOrder::class, $oxOrder);
        /** @var RefundService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(RefundService::class)
                 ->setConstructorArgs([$oxOrder, $afterpayOrder])
                 ->setMethods(['executeRequestFromVatSplittedRefundFields', 'parseResponse'])
                 ->getMock();
        $sut
            ->expects($this->once())
            ->method('executeRequestFromVatSplittedRefundFields')
            ->will($this->returnValue(123));

        $sut
            ->expects($this->once())
            ->method('parseResponse')
            ->will($this->returnValue('###OK###'));

        // run
        $this->assertEquals('###OK###', $sut->refund(123, 'SomeApiKey'));
    }
}
