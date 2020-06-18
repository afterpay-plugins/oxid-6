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
 * Class PaymentEntity: Entity for payment data.
 */
class PaymentEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Constants for type property.
     */
    public const TYPE_INVOICE = 'Invoice';
    public const TYPE_DEBITNOTE = 'Invoice'; // [sic.]
    public const TYPE_INSTALLMENT = 'Installment';
    public const TYPE_CONSOLIDATED_INVOICE = 'Consolidatedinvoice';

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
}
