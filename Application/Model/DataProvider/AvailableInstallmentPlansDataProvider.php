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

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

/**
 * Class AvailableInstallmentPlansDataProvider
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class AvailableInstallmentPlansDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * @param double $dAmount
     *
     * @return AvailableInstallmentPlansEntity
     */
    public function getDataObject($dAmount)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansEntity::class);
        $dataObject->setAmount($dAmount);
        return $dataObject;
    }
}
