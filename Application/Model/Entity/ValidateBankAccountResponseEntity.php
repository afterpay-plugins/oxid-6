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

/**
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class ValidateBankAccountResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    public function getIsValid()
    {
        return $this->getData('isValid');
    }

    public function setIsValid($isValid)
    {
        return $this->setData('isValid', $isValid);
    }
}
