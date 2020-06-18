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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class CaptureServiceTest: Tests for CaptureService.
 */
class AvailablePaymentMethodsServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testConstruct()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class, Registry::getSession(), Registry::getLang(), $order);
        $this->assertInstanceOf(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class, $sut);
    }

    public function testgetAvailablePaymentMethods()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $entity->setPaymentMethods('LoremIpsum');

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertEquals('LoremIpsum', $sut->getAvailablePaymentMethods());
        // Caching:
        $this->assertEquals('LoremIpsum', $sut->getAvailablePaymentMethods());
    }

    public function testisInvoiceAvailableFalse()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $entity->setPaymentMethods('LoremIpsum');

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertFalse($sut->isInvoiceAvailable());
    }

    public function testisInvoiceAvailableTrue()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $paymentMethod->type = 'Invoice';
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertTrue($sut->isInvoiceAvailable());
    }

    public function testisSpecificInstallmentAvailableFalse()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $paymentMethod->type = 'Invoice';
        $paymentMethod->installmentProfileNumber = 1;
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertFalse($sut->isSpecificInstallmentAvailable(2));
    }

    public function testisSpecificInstallmentAvailableNoMethodsAtAll()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $sut->method('parseResponse')->willReturn(null);
        $this->assertFalse($sut->isSpecificInstallmentAvailable(2));
    }

    public function testisSpecificInstallmentAvailableTrue()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $paymentMethod->type = 'Installment';
        $paymentMethod->installment = new \stdClass();
        $paymentMethod->installment->installmentProfileNumber = 2;
        $paymentMethod->installment->numberOfInstallments = 1337;
        $paymentMethod->directDebit = new \stdClass();
        $paymentMethod->directDebit->available = true;
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertTrue($sut->isSpecificInstallmentAvailable(2));
    }

    public function testisDirectDebitAvailableFalse()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertFalse($sut->isDirectDebitAvailable());
    }

    public function testisDirectDebitAvailableTrue()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $paymentMethod->type = 'Invoice';
        $paymentMethod->directDebit = ['lorem'];
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertTrue($sut->isDirectDebitAvailable());
    }

    public function testgetNumberOfInstallmentsByProfileIdNonefound()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $paymentMethod->type = 'Installment';
        $paymentMethod->installment = new \stdClass();
        $paymentMethod->installment->installmentProfileNumber = 1;
        $paymentMethod->installment->numberOfInstallments = 1337;
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertNull($sut->getNumberOfInstallmentsByProfileId(1));
    }

    public function testgetNumberOfInstallmentsByProfileIdFound()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $sut =
            $this
                ->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class);
        $paymentMethod = new \stdClass();
        $paymentMethod->type = 'Installment';
        $paymentMethod->installment = new \stdClass();
        $paymentMethod->installment->installmentProfileNumber = 2;
        $paymentMethod->installment->numberOfInstallments = 1337;
        $paymentMethod->directDebit = new \stdClass();
        $paymentMethod->directDebit->available = true;
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);

        $this->assertEquals(1337, $sut->getNumberOfInstallmentsByProfileId(2));
    }
}
