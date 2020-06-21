<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * @method array getAvailableInstallmentPlans()
 * @method setAvailableInstallmentPlans(array $availableInstallmentPlans)
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class AvailableInstallmentPlansResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    public function getAvailableInstallmentPlanFormattings()
    {

        return [
            'basketAmount'                    => 'CURR',
            'numberOfInstallments'            => 'NUM',
            'installmentAmount'               => 'CURR',
            'firstInstallmentAmount'          => 'HIDE',
            'lastInstallmentAmount'           => 'HIDE',
            'interestRate'                    => 'PERCENT',
            'effectiveInterestRate'           => 'PERCENT',
            'effectiveAnnualPercentageRate'   => 'HIDE',
            'totalInterestAmount'             => 'CURR',
            'startupFee'                      => 'HIDE',
            'monthlyFee'                      => 'HIDE',
            'totalAmount'                     => 'CURR',
            'installmentProfileNumber'        => 'COMMENT',
            'readMore'                        => 'LINK',
        ];
    }
}
