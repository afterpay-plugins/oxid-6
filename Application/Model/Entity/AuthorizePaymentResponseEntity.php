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
 * Class AuthorizePaymentResponseEntity: Entitiy for the autorize payment response.
 */
class AuthorizePaymentResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Constants for outcome property.
     */
    public const OUTCOME_ACCEPTED = 'Accepted';
    public const OUTCOME_PENDING = 'Pending';
    public const OUTCOME_REJECTED = 'Rejected';

    /**
     * Getter for outcome property.
     *
     * @return string
     */
    public function getOutcome()
    {
        return $this->getData('outcome');
    }

    /**
     * Setter for outcome property.
     *
     * @param string $outcome
     */
    public function setOutcome($outcome)
    {
        $this->setData('outcome', $outcome);
    }

    /**
     * Getter for customer property.
     *
     * @return CustomerResponseEntity
     */
    public function getCustomer()
    {
        return $this->getData('customer');
    }

    /**
     * Setter for customer property.
     *
     * @param CustomerResponseEntity $customer
     */
    public function setCustomer(CustomerResponseEntity $customer)
    {
        $this->setData('customer', $customer);
    }

    /**
     * Getter for delivery customer property.
     *
     * @return CustomerResponseEntity
     */
    public function getDeliveryCustomer()
    {
        return $this->getData('deliveryCustomer');
    }

    /**
     * Setter for delivery customer property.
     *
     * @param CustomerResponseEntity $deliveryCustomer
     */
    public function setDeliveryCustomer(CustomerResponseEntity $deliveryCustomer)
    {
        $this->setData('deliveryCustomer', $deliveryCustomer);
    }

    /**
     * Getter for reservation id property.
     *
     * @return string
     */
    public function getReservationId()
    {
        return $this->getData('reservationId');
    }

    /**
     * Setter for reservation id property.
     *
     * @param string $reservationId
     */
    public function setReservationId($reservationId)
    {
        $this->setData('reservationId', $reservationId);
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
     * Getter for risk check messages property.
     *
     * @return ResponseMessageEntity[]
     */
    public function getRiskCheckMessages()
    {
        return $this->getData('riskCheckMessages');
    }

    /**
     * Setter for risk check messages property.
     *
     * @param ResponseMessageEntity[] $riskCheckMessages
     */
    public function setRiskCheckMessages($riskCheckMessages)
    {
        $this->setData('riskCheckMessages', $riskCheckMessages);
    }

    public function addAddress(ResponseMessageEntity $responseMessageEntity)
    {
        //Intentionally empty.
    }
}
