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

namespace Arvato\AfterpayModule\Application\Model\Parser;

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
        $this->aFields = [
            'availableInstallmentPlans',
        ];
        return parent::parse($object);
    }
}
