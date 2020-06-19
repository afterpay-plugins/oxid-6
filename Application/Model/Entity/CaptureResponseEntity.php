<?php

/**
 *
*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class CaptureResponseEntity: Entity for the capture response.
 */
class CaptureResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    /**
     * Getter for capturedAmount property.
     *
     * @return string
     */
    public function getCapturedAmount()
    {
        return $this->getData('capturedAmount');
    }

    /**
     * Setter for capturedAmount property.
     *
     * @param double $capturedAmount
     */
    public function setCapturedAmount($capturedAmount)
    {
        return $this->setData('capturedAmount', $capturedAmount);
    }

    /**
     * Getter for authorizedAmount property.
     *
     * @return string
     */
    public function getAuthorizedAmount()
    {
        return $this->getData('authorizedAmount');
    }

    /**
     * Setter for authorizedAmount property.
     *
     * @param double $authorizedAmount
     */
    public function setAuthorizedAmount($authorizedAmount)
    {
        return $this->setData('authorizedAmount', $authorizedAmount);
    }

    /**
     * Getter for remainingAuthorizedAmount property.
     *
     * @return string
     */
    public function getRemainingAuthorizedAmount()
    {
        return $this->getData('remainingAuthorizedAmount');
    }

    /**
     * Setter for remainingAuthorizedAmount property.
     *
     * @param double $remainingAuthorizedAmount
     */
    public function setRemainingAuthorizedAmount($remainingAuthorizedAmount)
    {
        return $this->setData('remainingAuthorizedAmount', $remainingAuthorizedAmount);
    }

    /**
     * Getter for captureNumber property.
     *
     * @return string
     */
    public function getCaptureNumber()
    {
        return $this->getData('captureNumber');
    }

    /**
     * Setter for captureNumber property.
     *
     * @param string $captureNumber
     */
    public function setCaptureNumber($captureNumber)
    {
        return $this->setData('captureNumber', $captureNumber);
    }
}
