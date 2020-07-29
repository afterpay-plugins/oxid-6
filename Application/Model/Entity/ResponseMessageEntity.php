<?php

/**
 *
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
    const TYPE_BUSINESS_ERROR = 'BusinessError';
    const TYPE_TECHNICAL_ERROR = 'TechnicalError';
    const TYPE_NOTIFICATION_MESSAGE = 'NotificationMessage';

    /**
     * Constants for action code property.
     */
    const ACTION_CODE_UNAVAILABLE = 'Unavailable';
    const ACTION_CODE_ASK_CONSUMER_TO_CONFIRM = 'AskConsumerToConfirm';
    const ACTION_CODE_ASK_CONSUMER_TO_REENTER_DATA = 'AskConsumerToReEnterData';
    const ACTION_CODE_OFFER_SECURE_PAYMENT_METHODS = 'OfferSecurePaymentMethods';
    const ACTION_CODE_REQUIRES_SSN = 'RequiresSsn';

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
