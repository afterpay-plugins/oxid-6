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

use stdClass;

/**
 * Class CaptureShippingEntity: Entity for capture Shipping call.
 */
class CaptureShippingEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    /**
     * Getter for checkout id property.
     *
     * @return stdClass $type
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * Setter for checkout id property.
     *
     * @param stdClass $type
     */
    public function setType($type)
    {
        $this->setData('type', $type);
    }

    /**
     * Getter for checkout id property.
     *
     * @return stdClass $shippingCompany
     */
    public function getShippingCompany()
    {
        return $this->getData('shippingCompany');
    }

    /**
     * Setter for checkout id property.
     *
     * @param stdClass $shippingCompany
     */
    public function setShippingCompany($shippingCompany)
    {
        $this->setData('shippingCompany', $shippingCompany);
    }

    /**
     * Getter for checkout id property.
     *
     * @return stdClass $trackingId
     */
    public function getTrackingId()
    {
        return $this->getData('trackingId');
    }

    /**
     * Setter for checkout id property.
     *
     * @param stdClass $trackingId
     */
    public function setTrackingId($trackingId)
    {
        $this->setData('trackingId', $trackingId);
    }
}
