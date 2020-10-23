<?php

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class CaptureShippingEntity: Entity for capture Shipping call.
 */
class CaptureShippingEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    /**
     * Getter for checkout id property.
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * Setter for checkout id property.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->setData('type', $type);
    }

    /**
     * Getter for checkout id property.
     *
     * @return string $shippingCompany
     */
    public function getShippingCompany()
    {
        return $this->getData('shippingCompany');
    }

    /**
     * Setter for checkout id property.
     *
     * @param string $shippingCompany
     */
    public function setShippingCompany($shippingCompany)
    {
        $this->setData('shippingCompany', $shippingCompany);
    }

    /**
     * Getter for checkout id property.
     *
     * @return string $trackingId
     */
    public function getTrackingId()
    {
        return $this->getData('trackingId');
    }

    /**
     * Setter for checkout id property.
     *
     * @param string $trackingId
     */
    public function setTrackingId($trackingId)
    {
        $this->setData('trackingId', $trackingId);
    }
}
