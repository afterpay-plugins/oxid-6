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

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansEntity;

/**
 * Class AvailableInstallmentPlansDataProvider
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class AvailableInstallmentPlansDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * @param double $amount
     *
     * @return AvailableInstallmentPlansEntity
     */
    public function getDataObject($amount)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansEntity::class);
        $dataObject->setAmount($amount);
        return $dataObject;
    }
}
