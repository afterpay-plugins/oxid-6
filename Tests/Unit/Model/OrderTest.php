<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model;

use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class OrderTest: Tests for Order.
 */
class OrderTest extends UnitTestCase
{

    /**
     * Testing method isAfterpayPaymentType
     */
    public function testIsAfterpayPaymentTypeTrue()
    {
        $sut = oxNew(Order::class);
        $this->assertTrue($sut->isAfterpayPaymentType('afterpayFoobar'));
        $this->assertTrue($sut->isAfterpayPaymentType('afterpayInvoice'));
    }

    /**
     * Testing method isAfterpayPaymentType
     */
    public function testIsAfterpayPaymentTypeFalse()
    {
        $sut = oxNew(Order::class);
        $this->assertFalse($sut->isAfterpayPaymentType('oxidFoobar'));
        $this->assertFalse($sut->isAfterpayPaymentType(''));
    }

    /**
     * Testing method GetAfterpayOrder
     */
    public function testGetAfterpayOrderReturnEmpty()
    {
        $sut = oxNew(Order::class);
        $AfterpayOrder = $sut->getAfterpayOrder();
        $this->assertFalse($AfterpayOrder->isLoaded());
    }

    /**
     * Testing method GetAfterpayOrder
     */
    public function testGetAfterpayOrderReturnFound()
    {

        /** @ var oxOrder $sut */
        $sut = oxNew(Order::class);
        $sut->setId('UNITTEST123');
        $sut->oxorder__oxpaymenttype = new Field('afterpayinvoice');

        $afterpayOrder = oxNew(AfterpayOrder::class, $sut);
        $afterpayOrder->save();

        // Selftest
        $this->assertEquals('UNITTEST123', $afterpayOrder->getId());

        // SUT Test
        $AfterpayOrder = $sut->getAfterpayOrder();
        $this->assertTrue($AfterpayOrder->isLoaded());

        $afterpayOrder->delete();
    }
}
