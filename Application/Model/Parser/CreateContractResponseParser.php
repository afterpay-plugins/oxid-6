<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class CreateContractResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    protected $aFields = ['contractId', 'requireCustomerConfirmation', 'contractList'];
}
