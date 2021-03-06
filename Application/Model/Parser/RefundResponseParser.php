<?php

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Class RefundResponseParser
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 *
 * @codeCoverageIgnore
 */
class RefundResponseParser extends Parser
{
    protected $fields = ['totalCapturedAmount', 'totalAuthorizedAmount', 'refundNumbers'];
}
