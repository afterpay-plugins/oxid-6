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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model;

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

        $afterpayOrder = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\AfterpayOrder::class, $sut);
        $afterpayOrder->save();

        // Selftest
        $this->assertEquals('UNITTEST123', $afterpayOrder->getId());

        // SUT Test
        $AfterpayOrder = $sut->getAfterpayOrder();
        $this->assertTrue($AfterpayOrder->isLoaded());

        $afterpayOrder->delete();
    }
}
