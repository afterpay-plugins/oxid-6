<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category  module
 * @package   afterpay
 * @author    OXID Professional services
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace Arvato\AfterpayModule\Core;

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
     * @param string $sIBAN - Used for Debit and Installment
     * @param string $sBIC - Used for Debit and Installment
     * @param int $iSelectedInstallmentPlanProfileId - Used for           Installment
     * @param int $iNumberOfInstallments - Used for           Installment
     *
     * @return string contractId
     *
     * @codeCoverageIgnore We really can't test this without literally mockiung all 3 lines.
     * @deprecated since version 2.0.5
     */
    public function createContract(
        $paymentId,
        $sIBAN,
        $sBIC,
        $iSelectedInstallmentPlanProfileId = null,
        $iNumberOfInstallments = null
    ) {
        $response = $this->executeRequest($this->_afterpayCheckoutId, $paymentId, $sIBAN, $sBIC, $iSelectedInstallmentPlanProfileId, $iNumberOfInstallments);
        $this->_entity = $this->parseResponse($response);
        return $this->_entity->getContractId();
    }

    /**
     * @param $afterpayCheckoutID - Used for Debit and Installment
     *
     * @param string $paymentId paymentId               - Used for Debit and Installment
     *
     * @param string $sIBAN - Used for Debit and Installment
     * @param string $sBIC - Used for Debit and Installment
     *
     * @param int $iSelectedInstallmentPlanProfileId - Used for           Installment
     * @param int $iNumberOfInstallments - Used for           Installment
     *
     * @return stdClass|stdClass[]
     * @throws PaymentException
     */
    public function executeRequest(
        $afterpayCheckoutID,
        $paymentId,
        $sIBAN,
        $sBIC,
        $iSelectedInstallmentPlanProfileId = null,
        $iNumberOfInstallments = null
    ) {
        if (!$afterpayCheckoutID || !$paymentId || !$sIBAN || !$sBIC) {
            throw new \Arvato\AfterpayModule\Core\Exception\PaymentException(
                'Missing either of $afterpayCheckoutID, $paymentId, $sIBAN, $sBIC: '
                . "$afterpayCheckoutID, $paymentId, $sIBAN, $sBIC"
            );
        }

        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider::class)->getPayment($paymentId, $sIBAN, $sBIC, $iSelectedInstallmentPlanProfileId, $iNumberOfInstallments);
        $data = $dataObject->exportData('paymentInfo');
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getCreateContractClient($afterpayCheckoutID)->execute($data);
    }
}
