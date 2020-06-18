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
 * Class ResponseMessageEntity: Entitiy for response messages.
 */
class ResponseMessageEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Constants for type property.
     */
    public const TYPE_BUSINESS_ERROR = 'BusinessError';
    public const TYPE_TECHNICAL_ERROR = 'TechnicalError';
    public const TYPE_NOTIFICATION_MESSAGE = 'NotificationMessage';

    /**
     * Constants for action code property.
     */
    public const ACTION_CODE_UNAVAILABLE = 'Unavailable';
    public const ACTION_CODE_ASK_CONSUMER_TO_CONFIRM = 'AskConsumerToConfirm';
    public const ACTION_CODE_ASK_CONSUMER_TO_REENTER_DATA = 'AskConsumerToReEnterData';
    public const ACTION_CODE_OFFER_SECURE_PAYMENT_METHODS = 'OfferSecurePaymentMethods';
    public const ACTION_CODE_REQUIRES_SSN = 'RequiresSsn';

    /**
     * Getter for type property.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * Setter for type property.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->setData('type', $type);
    }

    /**
     * Getter for code property.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getData('code');
    }

    /**
     * Setter for code property.
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->setData('code', $code);
    }

    /**
     * Getter for message property.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getData('message');
    }

    /**
     * Setter for message property.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->setData('message', $message);
    }

    /**
     * Getter for customer facing message property.
     *
     * @return string
     */
    public function getCustomerFacingMessage()
    {
        return $this->getData('customerFacingMessage');
    }

    /**
     * Setter for customer facing message property.
     *
     * @param string $customerFacingMessage
     */
    public function setCustomerFacingMessage($customerFacingMessage)
    {
        $this->setData('customerFacingMessage', $customerFacingMessage);
    }

    /**
     * Getter for action code property.
     *
     * @return string
     */
    public function getActionCode()
    {
        return $this->getData('actionCode');
    }

    /**
     * Setter for action code property.
     *
     * @param string $actionCode
     */
    public function setActionCode($actionCode)
    {
        $this->setData('actionCode', $actionCode);
    }

    /**
     * Getter for field reference property.
     *
     * @return string
     */
    public function getFieldReference()
    {
        return $this->getData('fieldReference');
    }

    /**
     * Setter for field reference property.
     *
     * @param string $fieldReference
     */
    public function setFieldReference($fieldReference)
    {
        $this->setData('fieldReference', $fieldReference);
    }
}
