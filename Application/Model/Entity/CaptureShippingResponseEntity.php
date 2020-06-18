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
