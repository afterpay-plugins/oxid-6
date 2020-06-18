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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class AuthorizePaymentEntityTest: unit tests for AuthorizePaymentEntity.
 */
class AuthorizePaymentEntityTest extends \OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $payment = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\PaymentEntity::class);

        $payment->setType(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_DEBITNOTE);
        $customer = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class);
        $customer->setCustomerCategory(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);
        $deliveryCustomer = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class);
        $deliveryCustomer->setCustomerCategory(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);

        $testData = [
            'payment'                       => $payment,
            'checkoutId'                    => '12345abcde',
            'merchantId'                    => 'abcde12345',
            'customer'                      => $customer,
            'deliveryCustomer'              => $deliveryCustomer,
            'order'                         => oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderEntity::class),
            'parentTransactionReference'    => 'x1x2x3x'
        ];

        $testObject = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\AuthorizePaymentEntity::class);
        $this->testGetSet($testObject, $testData);

        $this->assertEquals((object) [
            'payment'                       => (object) ['type' => \OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_DEBITNOTE],
            'checkoutId'                    => '12345abcde',
            'merchantId'                    => 'abcde12345',
            'customer'                      => (object) ['customerCategory' => \OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON],
            'deliveryCustomer'              => (object) ['customerCategory' => \OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON],
            'order'                         => (object) [],
            'parentTransactionReference'    => 'x1x2x3x'
        ], $testObject->exportData(), 'exported object not valid');
    }
}
