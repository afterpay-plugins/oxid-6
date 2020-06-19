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

use Arvato\AfterpayModule\Application\Model\Entity\ValidateBankAccountEntity;

/**
 * Class ValidateBankAccountDataProvider
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class ValidateBankAccountDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * @param string $IBAN
     * @param string $BIC
     *
     * @return ValidateBankAccountEntity
     */
    public function getDataObject($IBAN, $BIC)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\ValidateBankAccountEntity::class);
        $dataObject->setBankAccount($IBAN);
        $dataObject->setBankCode($BIC);
        return $dataObject;
    }
}
