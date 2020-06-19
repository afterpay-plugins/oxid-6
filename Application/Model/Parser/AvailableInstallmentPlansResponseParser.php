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

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\Entity;
use stdClass;

/**
 * Class AvailableInstallmentPlansResponseParser
 */
class AvailableInstallmentPlansResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{

    /**
     * @param stdClass $object
     *
     * @return Entity
     */
    public function parse(\stdClass $object)
    {
        $this->fields = [
            'availableInstallmentPlans',
        ];
        return parent::parse($object);
    }
}
