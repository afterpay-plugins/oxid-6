<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

class RefundServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testConstruct()
    {
        $this->assertInstanceOf(
            \Arvato\AfterpayModule\Core\RefundService::class,
            oxNew(\Arvato\AfterpayModule\Core\RefundService::class, oxNew(\OxidEsales\Eshop\Application\Model\Order::class))
        );
    }

    public function testRefundException()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class);
        $sut = oxNew(\Arvato\AfterpayModule\Core\RefundService::class, oxNew(\OxidEsales\Eshop\Application\Model\Order::class));
        $sut->refund(null, 'SomeApiKey');
    }

    public function testRefundOk()
    {
        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $AfterpayOrder = oxNew(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::class, $oxOrder);
        $sut =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\RefundService::class)
                ->setConstructorArgs([$oxOrder, $AfterpayOrder])
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
