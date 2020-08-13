<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Application\Model\Entity\Entity;
use Arvato\AfterpayModule\Core\AvailablePaymentMethodsService;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

/**
 * Class CaptureServiceTest: Tests for CaptureService.
 */
class AvailablePaymentMethodsServiceTest extends UnitTestCase
{

    public function testConstruct()
    {
        $order = oxNew(Order::class);
        $sut = oxNew(AvailablePaymentMethodsService::class, Registry::getSession(), Registry::getLang(), $order);
        $this->assertInstanceOf(AvailablePaymentMethodsService::class, $sut);
    }

    public function testgetAvailablePaymentMethods()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $entity->setPaymentMethods('LoremIpsum');

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertEquals('LoremIpsum', $sut->getAvailablePaymentMethods());
        // Caching:
        $this->assertEquals('LoremIpsum', $sut->getAvailablePaymentMethods());
    }

    public function testisInvoiceAvailableFalse()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $entity->setPaymentMethods('LoremIpsum');

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertFalse($sut->isInvoiceAvailable());
    }

    public function testisInvoiceAvailableTrue()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $paymentMethod = new stdClass();
        $paymentMethod->type = 'Invoice';
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertTrue($sut->isInvoiceAvailable());
    }

    public function testisSpecificInstallmentAvailableFalse()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $paymentMethod = new stdClass();
        $paymentMethod->type = 'Invoice';
        $paymentMethod->installmentProfileNumber = 1;
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertFalse($sut->isSpecificInstallmentAvailable(2));
    }

    public function testisSpecificInstallmentAvailableNoMethodsAtAll()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $sut->method('parseResponse')->willReturn(null);
        $this->assertFalse($sut->isSpecificInstallmentAvailable(2));
    }

    public function testisSpecificInstallmentAvailableTrue()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $paymentMethod = new stdClass();
        $paymentMethod->type = 'Installment';
        $paymentMethod->installment = new stdClass();
        $paymentMethod->installment->installmentProfileNumber = 2;
        $paymentMethod->installment->numberOfInstallments = 1337;
        $paymentMethod->directDebit = new stdClass();
        $paymentMethod->directDebit->available = true;
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertTrue($sut->isSpecificInstallmentAvailable(2));
    }

    public function testisDirectDebitAvailableFalse()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $paymentMethod = new stdClass();
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertFalse($sut->isDirectDebitAvailable());
    }

    public function testisDirectDebitAvailableTrue()
    {
        $order = oxNew(Order::class);
        /** @var AvailablePaymentMethodsService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this
                ->getMockBuilder(AvailablePaymentMethodsService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang(), $order])
                ->setMethods(['executeRequestFromSessionData', 'parseResponse'])
                ->getMock();

        $entity = oxNew(Entity::class);
        $paymentMethod = new stdClass();
        $paymentMethod->type = 'Invoice';
        $paymentMethod->directDebit = ['lorem'];
        $entity->setPaymentMethods([$paymentMethod]);

        $sut->method('parseResponse')->willReturn($entity);
        $this->assertTrue($sut->isDirectDebitAvailable());
    }
}
