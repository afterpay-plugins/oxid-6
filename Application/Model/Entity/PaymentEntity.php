<?php

/**
 *
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
    const TYPE_INVOICE = 'Invoice';
    const TYPE_DEBITNOTE = 'Invoice'; // [sic.]
    const TYPE_INSTALLMENT = 'Installment';
    const TYPE_CONSOLIDATED_INVOICE = 'Consolidatedinvoice';

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
