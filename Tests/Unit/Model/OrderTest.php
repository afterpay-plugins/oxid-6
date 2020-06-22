<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model;

/**
 * Class OrderTest: Tests for Order.
 */
class OrderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Testing method isAfterpayPaymentType
     */
    public function testIsAfterpayPaymentTypeTrue()
    {
        $sut = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $this->assertTrue($sut->isAfterpayPaymentType('afterpayFoobar'));
        $this->assertTrue($sut->isAfterpayPaymentType('afterpayInvoice'));
    }

    /**
     * Testing method isAfterpayPaymentType
     */
    public function testIsAfterpayPaymentTypeFalse()
    {
        $sut = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $this->assertFalse($sut->isAfterpayPaymentType('oxidFoobar'));
        $this->assertFalse($sut->isAfterpayPaymentType(''));
    }

    /**
     * Testing method GetAfterpayOrder
     */
    public function testGetAfterpayOrderReturnEmpty()
    {
        $sut = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $AfterpayOrder = $sut->getAfterpayOrder();
        $this->assertFalse($AfterpayOrder->isLoaded());
    }

    /**
     * Testing method GetAfterpayOrder
     */
    public function testGetAfterpayOrderReturnFound()
    {

        /** @ var oxOrder $sut */
        $sut = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut->setId('UNITTEST123');
        $sut->oxorder__oxpaymenttype = new \OxidEsales\Eshop\Core\Field('afterpayinvoice');

        $afterpayOrder = oxNew(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::class, $sut);
        $afterpayOrder->save();

        // Selftest
        $this->assertEquals('UNITTEST123', $afterpayOrder->getId());

        // SUT Test
        $AfterpayOrder = $sut->getAfterpayOrder();
        $this->assertTrue($AfterpayOrder->isLoaded());

        $afterpayOrder->delete();
    }
}
