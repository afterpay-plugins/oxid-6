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
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class RefundEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    /**
     * Getter for capture number property.
     *
     * @return string
     */
    public function getCaptureNumber()
    {
        return $this->getData('captureNumber');
    }

    /**
     * Setter for capture number property.
     *
     * @param string $captureNumber
     */
    public function setCaptureNumber($captureNumber)
    {
        $this->setData('captureNumber', $captureNumber);
    }

    /**
     * Getter for items property.
     *
     * @return OrderItemEntity[]
     */
    public function getItems()
    {
        return $this->getData('orderItems');
    }

    /**
     * Setter for items property.
     *
     * @param OrderItemEntity[] $items
     */
    public function setItems($items)
    {
        $this->setData('orderItems', $items);
    }
}
