<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Core\Exception\PaymentException;
use stdClass;

/**
 * Class CreateContractService
 */
class CreateContractService extends \Arvato\AfterpayModule\Core\Service
{

    protected $_afterpayCheckoutId;

    /**
     * Standard constructor.
     *
     * @param string $afterpayCheckoutId
     */
    public function __construct($afterpayCheckoutId)
    {
        $this->_afterpayCheckoutId = $afterpayCheckoutId;
    }

    /**
     * Performs the autorize payment call.
     *
     * @param string $paymentId paymentId               - Used for Debit and Installment
     * @param string $IBAN - Used for Debit and Installment
     * @param string $BIC - Used for Debit and Installment
     * @param int $selectedInstallmentPlanProfileId - Used for           Installment
     * @param int $numberOfInstallments - Used for           Installment
     *
     * @return string contractId
     *
     * @codeCoverageIgnore We really can't test this without literally mockiung all 3 lines.
     * @deprecated since version 2.0.5
     */
    public function createContract(
        $paymentId,
        $IBAN,
        $BIC,
        $selectedInstallmentPlanProfileId = null,
        $numberOfInstallments = null
    ) {
        $response = $this->executeRequest($this->_afterpayCheckoutId, $paymentId, $IBAN, $BIC, $selectedInstallmentPlanProfileId, $numberOfInstallments);
        $this->_entity = $this->parseResponse($response);
        return $this->_entity->getContractId();
    }

    /**
     * @param $afterpayCheckoutID - Used for Debit and Installment
     *
     * @param string $paymentId paymentId               - Used for Debit and Installment
     *
     * @param string $IBAN - Used for Debit and Installment
     * @param string $BIC - Used for Debit and Installment
     *
     * @param int $selectedInstallmentPlanProfileId - Used for           Installment
     * @param int $numberOfInstallments - Used for           Installment
     *
     * @return stdClass|stdClass[]
     * @throws PaymentException
     */
    public function executeRequest(
        $afterpayCheckoutID,
        $paymentId,
        $IBAN,
        $BIC,
        $selectedInstallmentPlanProfileId = null,
        $numberOfInstallments = null
    ) {
        if (!$afterpayCheckoutID || !$paymentId || !$IBAN || !$BIC) {
            throw new \Arvato\AfterpayModule\Core\Exception\PaymentException(
                'Missing either of $afterpayCheckoutID, $paymentId, $IBAN, $BIC: '
                . "$afterpayCheckoutID, $paymentId, $IBAN, $BIC"
            );
        }

        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class)->getPayment($paymentId, $IBAN, $BIC, $selectedInstallmentPlanProfileId, $numberOfInstallments);
        $data = $dataObject->exportData('paymentInfo');
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getCreateContractClient($afterpayCheckoutID)->execute($data);
    }
}
