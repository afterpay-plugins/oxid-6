<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

use stdClass;

/**
 * Class CaptureEntity: Entity for capture call.
 */
class CaptureEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Getter for checkout id property.
     *
     * @return stdClass $orderDetails
     */
    public function getOrderDetails()
    {
        return $this->getData('orderDetails');
    }

    /**
     * Setter for checkout id property.
     *
     * @param stdClass $orderDetails
     */
    public function setOrderDetails($orderDetails)
    {
        $this->setData('orderDetails', $orderDetails);
    }
}
