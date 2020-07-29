<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core\Exception;

/**
 * Class PaymentException: Exception class of curl errors.
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class PaymentException extends \OxidEsales\Eshop\Core\Exception\StandardException
{
}
