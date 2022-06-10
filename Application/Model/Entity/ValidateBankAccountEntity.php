<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class ValidateBankAccountEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    public function getBankAccount()
    {
        return $this->getData('bankAccount');
    }

    public function setBankAccount($IBAN)
    {
        return $this->setData('bankAccount', $IBAN);
    }

    public function getBankCode()
    {
        return $this->getData('bankCode');
    }
}
