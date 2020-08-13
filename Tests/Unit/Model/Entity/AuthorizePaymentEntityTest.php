<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity;
use Arvato\AfterpayModule\Application\Model\Entity\OrderEntity;
use Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity;

/**
 * Class AuthorizePaymentEntityTest: unit tests for AuthorizePaymentEntity.
 */
class AuthorizePaymentEntityTest extends EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $payment = oxNew(PaymentEntity::class);

        $payment->setType(PaymentEntity::TYPE_DEBITNOTE);
        $customer = oxNew(CheckoutCustomerEntity::class);
        $customer->setCustomerCategory(CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);
        $deliveryCustomer = oxNew(CheckoutCustomerEntity::class);
        $deliveryCustomer->setCustomerCategory(CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);

        $testData = [
            'payment'                    => $payment,
            'checkoutId'                 => '12345abcde',
            'merchantId'                 => 'abcde12345',
            'customer'                   => $customer,
            'deliveryCustomer'           => $deliveryCustomer,
            'order'                      => oxNew(OrderEntity::class),
            'parentTransactionReference' => 'x1x2x3x',
        ];

        $testObject = oxNew(AuthorizePaymentEntity::class);
        $this->getSet($testObject, $testData);

        $this->assertEquals((object) [
            'payment'                    => (object) ['type' => PaymentEntity::TYPE_DEBITNOTE],
            'checkoutId'                 => '12345abcde',
            'merchantId'                 => 'abcde12345',
            'customer'                   => (object) ['customerCategory' => CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON],
            'deliveryCustomer'           => (object) ['customerCategory' => CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON],
            'order'                      => (object) [],
            'parentTransactionReference' => 'x1x2x3x',
        ], $testObject->exportData(), 'exported object not valid');
    }
}
