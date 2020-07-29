<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class AuthorizePaymentEntityTest: unit tests for AuthorizePaymentEntity.
 */
class AuthorizePaymentEntityTest extends \Arvato\AfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $payment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);

        $payment->setType(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_DEBITNOTE);
        $customer = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class);
        $customer->setCustomerCategory(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);
        $deliveryCustomer = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class);
        $deliveryCustomer->setCustomerCategory(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);

        $testData = [
            'payment'                       => $payment,
            'checkoutId'                    => '12345abcde',
            'merchantId'                    => 'abcde12345',
            'customer'                      => $customer,
            'deliveryCustomer'              => $deliveryCustomer,
            'order'                         => oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderEntity::class),
            'parentTransactionReference'    => 'x1x2x3x'
        ];

        $testObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentEntity::class);
        $this->testGetSet($testObject, $testData);

        $this->assertEquals((object) [
            'payment'                       => (object) ['type' => \Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_DEBITNOTE],
            'checkoutId'                    => '12345abcde',
            'merchantId'                    => 'abcde12345',
            'customer'                      => (object) ['customerCategory' => \Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON],
            'deliveryCustomer'              => (object) ['customerCategory' => \Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON],
            'order'                         => (object) [],
            'parentTransactionReference'    => 'x1x2x3x'
        ], $testObject->exportData(), 'exported object not valid');
    }
}
