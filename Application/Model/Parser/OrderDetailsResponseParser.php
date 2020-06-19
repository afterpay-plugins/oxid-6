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

/**
 * Class ValidateBankAccountResponseParser
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class OrderDetailsResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    protected $aFields = ['orderDetails', 'captures', 'refunds'];
}
