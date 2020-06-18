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

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class AuthorizePaymentEntity: Entity for authorize payment call.
 */
class AuthorizePaymentEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Getter for payment property.
     *
     * @return PaymentEntity
     */
    public function getPayment()
    {
        return $this->getData('payment');
    }

    /**
     * Setter for payment property.
     *
     * @param PaymentEntity $payment
     */
    public function setPayment(PaymentEntity $payment)
    {
        $this->setData('payment', $payment);
    }

    /**
     * Getter for checkout id property.
     *
     * @return string
     */
    public function getCheckoutId()
    {
        return $this->getData('checkoutId');
    }

    /**
     * Setter for checkout id property.
     *
     * @param string $checkoutId
     */
    public function setCheckoutId($checkoutId)
    {
        $this->setData('checkoutId', $checkoutId);
    }

    /**
     * Getter for merchant id property.
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getData('merchantId');
    }

    /**
     * Setter for merchant id property.
     *
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->setData('merchantId', $merchantId);
    }

    /**
     * Getter for customer property.
     *
     * @return CheckoutCustomerEntity
     */
    public function getCustomer()
    {
        return $this->getData('customer');
    }

    /**
     * Setter for customer property.
     *
     * @param CheckoutCustomerEntity $customer
     */
    public function setCustomer(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity $customer)
    {
        $this->setData('customer', $customer);
    }

    /**
     * Getter for delivery customer property.
     *
     * @return CheckoutCustomerEntity
     */
    public function getDeliveryCustomer()
    {
        return $this->getData('deliveryCustomer');
    }

    /**
     * Setter for delivery customer property.
     *
     * @param CheckoutCustomerEntity $deliveryCustomer
     */
    public function setDeliveryCustomer(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity $deliveryCustomer)
    {
        $this->setData('deliveryCustomer', $deliveryCustomer);
    }

    /**
     * Getter for order property.
     *
     * @return OrderEntity
     */
    public function getOrder()
    {
        return $this->getData('order');
    }

    /**
     * Setter for order property.
     *
     * @param OrderEntity $order
     */
    public function setOrder(\Arvato\AfterpayModule\Application\Model\Entity\OrderEntity $order)
    {
        $this->setData('order', $order);
    }

    /**
     * Getter for parent transaction property.
     *
     * @return string
     */
    public function getParentTransactionReference()
    {
        return $this->getData('parentTransactionReference');
    }

    /**
     * Setter for parent transaction property.
     *
     * @param string $parentTransactionReference
     */
    public function setParentTransactionReference($parentTransactionReference)
    {
        $this->setData('parentTransactionReference', $parentTransactionReference);
    }
}
