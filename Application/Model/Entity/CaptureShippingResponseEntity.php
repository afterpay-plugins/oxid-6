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
 * Class arvatoAfterpayCaptureResponseShippingEntity: Entitiy for the capture Shipping response.
 */
class CaptureShippingResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    /**
     * Getter for shippingNumber property.
     *
     * @return string
     */
    public function getShippingNumber()
    {
        return $this->getData('shippingNumber');
    }

    /**
     * Setter for shippingNumber property.
     *
     * @param string $shippingNumber as returned by API (starting with 1 for every order-capture)
     */
    public function setShippingNumber($shippingNumber)
    {
        return $this->setData('shippingNumber', $shippingNumber);
    }
}
