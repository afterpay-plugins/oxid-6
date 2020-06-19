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
 * Class VoidEntity
 */
class VoidEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * @return stdClass $cancellationDetails
     */
    public function getCancellationDetails()
    {
        return $this->getData('cancellationDetails');
    }

    /**
     * @param stdClass $cancellationDetails
     */
    public function setCancellationDetails($cancellationDetails)
    {
        $this->setData('cancellationDetails', $cancellationDetails);
    }
}
