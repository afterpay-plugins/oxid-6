<?php

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Class AvailablePaymentMethodsResponseParser: Parser for the capture response.
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 *
 * @codeCoverageIgnore
 */
class AvailablePaymentMethodsResponseParser extends Parser
{
    protected $fields = [
        'checkoutId',
        'outcome',
        'customer',
        'paymentMethods',
    ];
}
