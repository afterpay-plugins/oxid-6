<?php

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\ValidateBankAccountResponseEntity;
use stdClass;

/**
 * Class ValidateBankAccountResponseParser
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class ValidateBankAccountResponseParser extends Parser
{
    /**
     * Parses a standard object into a entity.
     *
     * @param stdClass $object
     *
     * @return ValidateBankAccountResponseEntity
     */
    public function parse(stdClass $object)
    {
        $responseMessage = oxNew(ValidateBankAccountResponseEntity::class);
        if (isset($object->isValid)) {
            $responseMessage->setIsValid($object->isValid);
        }
        if (isset($object->message)) {
            $responseMessage->setErrors([$object->message]);
        }
        return $responseMessage;
    }
}
