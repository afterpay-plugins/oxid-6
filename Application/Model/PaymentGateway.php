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

namespace Arvato\AfterpayModule\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use Arvato\AfterpayModule\Core\AuthorizePaymentService;
use Arvato\AfterpayModule\Core\AvailablePaymentMethodsService;
use Arvato\AfterpayModule\Core\CreateContractService;

/**
 * Class PaymentGateway
 *
 * Injects into finalizeOrder-Process, so the order success depends on Afterpay
 *  IF the order is to be paid by Afterpay.
 *
 * Why we don't extend PaymentGateway_parent here:
 *  We will not stack multiple payment gateways,
 *  but load exactly the payment gateway that is needed for the current payment type.
 *  We do not want multiple payment providers to fire simultaneously!
 */
class PaymentGateway extends \OxidEsales\Eshop\Application\Model\PaymentGateway //[s.i.c. see above]
{

    /**
     * Executes payment, returns true on success.
     *
     * @param double $dAmount Goods amount
     * @param Order &$oOrder User ordering object
     *
     * @return bool Success, false on error
     * @extend executePayment
     */
    public function executePayment($dAmount, &$oOrder) //Superfluous ampersand to match parents definition
    {

        try {
            // Reset some session data to be generated during upcoming process
            $this->resetArvatoSessionVars();

            // If we are not in an afterpay payment (how did we get here in the first place?)
            // just go with the default payment gateway.
            if (!$oOrder->isAfterpayPaymentType()) {
                return $this->dereferToOtherPaymentProviders($dAmount, $oOrder);
            }

            if (!$oOrder->isAfterpayDebitNote() && !$oOrder->isAfterpayInvoice() && !$oOrder->isAfterpayInstallment()) {
                return $this->dereferToOtherPaymentProviders($dAmount, $oOrder);
            }

            $result = $this->dereferToPaymentHandling($oOrder);
            $this->resetArvatoSessionVars();

            if (
                !$result
                && oxNew(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
                    ->getOrderStateSelectInstallmentConstant() != $this->_iLastErrorNo
                && oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class)
                    ->getOrderStateCheckAddressConstant() != $this->_iLastErrorNo
            ) {
                // Set error to "Select other payment"
                $this->_iLastErrorNo = 5;
            }

            return $result;
        } catch (Exception $exception) {
            $this->resetArvatoSessionVars();
            return false;
        }
    }

    /**
     * @param $oOrder
     *
     * @return bool False on Error
     * @throws PaymentException
     */
    protected function dereferToPaymentHandling($oOrder)
    {

        // Get Order No. so it can be used as request identifier.
        // That might leave a hole in the order numbering if the payment gets rejected.
        $oOrder->setNumber();

        $success = true;

        if ($oOrder->isAfterpayDebitNote()) {
            oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)
                ->saveApiKeyToSession(false);
            // Call on DebitNote Only
            $success = $this->handleDebitNote($oOrder);
        }

        if ($oOrder->isAfterpayInstallment()) {
            oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)
                ->saveApiKeyToSession(true);
            // Call on DebitNote Only
            $success = $this->handleInstallment($oOrder);
        }
        if ($oOrder->isAfterpayInvoice()) {
            oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)
                ->saveApiKeyToSession(false);
        }

        if ($success) {
            // Call on every afterpay payment
            $success = $this->handleInvoice($oOrder);
        }

        return $success;
    }

    /**
     * @param $dAmount
     * @param $oOrder
     *
     * @return bool Success
     * @codeCoverageIgnore Mocking helper
     */
    protected function dereferToOtherPaymentProviders($dAmount, $oOrder)
    {
        $defaultPaymentGateway = oxNew(\OxidEsales\Eshop\Application\Model\PaymentGateway::class);
        return $defaultPaymentGateway->executePayment($dAmount, $oOrder);
    }

    /**
     * @param $oOrder
     *
     * @return bool Success False on Error
     */
    protected function handleInvoice($oOrder)
    {

        $service = $this->getAuthorizePaymentService($oOrder);
        $response = $service->authorizePayment($oOrder);

        $this->_sLastError = null;
        if ($response != 'Accepted') {
            $oOrder->resetNumber();
            $this->_sLastError = $service->getErrorMessages();
            $this->_iLastErrorNo = $service->getLastErrorNo();
            return false;
        }

        $aporder = oxNew(AfterpayOrder::class, $oOrder);
        $aporder->fillBySession(Registry::getSession());
        $aporder->save();

        return true;
    }

    /**
     * @param oxOrder $oOrder
     *
     * @return string $contractId False on Error
     */
    public function handleDebitNote($oOrder)
    {
        // Bank account ok? - Redundant to former call but makes process tamper-proof

        $apdebitbankaccount = $apdebitbankcode = null;
        foreach ($this->_oPaymentInfo->_aDynValues as $dynValue) {
            if ('apdebitbankaccount' === $dynValue->name) {
                $apdebitbankaccount = $dynValue->value;
            }
            if ('apdebitbankcode' === $dynValue->name) {
                $apdebitbankcode = $dynValue->value;
            }
        }

        if (
            !$apdebitbankaccount ||
            !$apdebitbankcode ||
            !$this->getValidateBankAccountService()->isValid($apdebitbankaccount, $apdebitbankcode)
        ) {
            return false;
        }

        // Direct Debit available?

        if (!$this->getAvailablePaymentMethodsService($oOrder)->isDirectDebitAvailable()) {
            return false;
        }

        $this->getSession()->setVariable('arvatoAfterpayIBAN', $apdebitbankaccount);
        $this->getSession()->setVariable('arvatoAfterpayBIC', $apdebitbankcode);

        /*
        * @deprecated since version 2.0.5

        // Create Contract
        $afterpayCheckoutId = Registry::getSession()->getVariable('arvatoAfterpayCheckoutId');
        $service = $this->getCreateContractService($afterpayCheckoutId);
        $paymentType = $oOrder->oxorder__oxpaymenttype->value;
        return $service->createContract($paymentType, $apdebitbankaccount, $apdebitbankcode);
        */

        return true;
    }

    /**
     * @param oxOrder $oOrder
     *
     * @return string $contractId False on error
     */
    public function handleInstallment($oOrder)
    {

        // Bank account ok? - Redundant to former call but makes process tamper-proof

        list($sBIC, $sIBAN) = $this->gatherIBANandBIC();

        if (!$sIBAN || !$sBIC || !$this->getValidateBankAccountService()->isValid($sIBAN, $sBIC)) {
            return false;
        }

        $this->getSession()->setVariable('arvatoAfterpayIBAN', $sIBAN);
        $this->getSession()->setVariable('arvatoAfterpayBIC', $sBIC);

        // Installment profile selected?

        $aDynValue = $this->getSession()->getVariable('dynvalue');

        if (!isset($aDynValue['afterpayInstallmentProfileId'])) {
            $this->_sLastError = 'NO INSTALLMENT PLAN SELECTED';
            return false;
        }

        $iSelectedInstallmentPlanProfileId = $aDynValue['afterpayInstallmentProfileId'];

        // Is selected installment plan available?

        $AvailablePaymentMethodsService = $this->getAvailablePaymentMethodsService($oOrder);

        if (
            !($AvailablePaymentMethodsService
            ->isSpecificInstallmentAvailable($iSelectedInstallmentPlanProfileId))
        ) {
            $this->_iLastErrorNo = $AvailablePaymentMethodsService->getLastErrorNo();
            $this->_sLastError = $AvailablePaymentMethodsService->getErrorMessages();
            return false;
        }

        $afterpayCheckoutId = Registry::getSession()->getVariable('arvatoAfterpayCheckoutId');
        $iNumberOfInstallments = $AvailablePaymentMethodsService
            ->getNumberOfInstallmentsByProfileId($iSelectedInstallmentPlanProfileId);

        /*
        * @deprecated since version 2.0.5
        $contractId = $this->createContract(
            $afterpayCheckoutId,
            $iSelectedInstallmentPlanProfileId,
            $iNumberOfInstallments,
            $sIBAN,
            $sBIC
        );

        Registry::getSession()->setVariable('arvatoAfterpayContractId', $contractId);
        */

        return $contractId;
    }

    /**
     * @return AuthorizePaymentService
     *
     * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
     * (only getters and setters), can be excluded from test coverage:
     * @codeCoverageIgnore
     */
    protected function getAuthorizePaymentService()
    {
        $session = Registry::getSession();
        $language = Registry::getLang();
        return oxNew(AuthorizePaymentService::class, $session, $language);
    }

    /**
     * @return AvailablePaymentMethodsService
     *
     * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
     * (only getters and setters), can be excluded from test coverage:
     * @codeCoverageIgnore
     */
    protected function getAvailablePaymentMethodsService($oOrder)
    {
        $session = Registry::getSession();
        $language = Registry::getLang();
        return oxNew(AvailablePaymentMethodsService::class, $session, $language, $oOrder);
    }

    /**
     * @return ValidateBankAccountService
     *
     * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
     * (only getters and setters), can be excluded from test coverage:
     * @codeCoverageIgnore
     */
    protected function getValidateBankAccountService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class);
    }

    /**
     * @param string $checkoutId
     *
     * @return CreateContractService
     * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
     * (only getters and setters), can be excluded from test coverage:
     * @codeCoverageIgnore
     */
    protected function getCreateContractService($checkoutId)
    {
        return oxNew(\Arvato\AfterpayModule\Core\CreateContractService::class, $checkoutId);
    }

    /**
     * Delets all arvatoAfterpay... session vars that are used to communicate
     * between the API calls of a single checkout process
     *
     * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
     * (only getters and setters), can be excluded from test coverage:
     *
     * @codeCoverageIgnore
     */
    protected function resetArvatoSessionVars()
    {
        $oxSession = Registry::getSession();
        $oxSession->deleteVariable('arvatoAfterpayCheckoutId');
        $oxSession->deleteVariable('arvatoAfterpayContractId');
        $oxSession->deleteVariable('arvatoAfterpayIBAN');
        $oxSession->deleteVariable('arvatoAfterpayBIC');
        $oxSession->deleteVariable('arvatoAfterpayApiKey');
        $oxSession->deleteVariable('arvatoAfterpayCustomerFacingMessage');
    }

    /**
     * @return array
     */
    protected function gatherIBANandBIC()
    {
        $sIBAN = $sBIC = null;

        foreach ($this->_oPaymentInfo->_aDynValues as $dynValue) {
            if ('apinstallmentbankaccount' === $dynValue->name) {
                $sIBAN = $dynValue->value;
            }
            if ('apinstallmentbankcode' === $dynValue->name) {
                $sBIC = $dynValue->value;
            }
        }
        return [$sBIC, $sIBAN];
    }

    /**
     * @param $afterpayCheckoutId
     * @param $iSelectedInstallmentPlanProfileId
     * @param $iNumberOfInstallments
     * @param $sIBAN
     * @param $sBIC
     *
     * @return string
     * @codeCoverageIgnore Mocking helper
     * @deprecated since version 2.0.5
     */
    protected function createContract(
        $afterpayCheckoutId,
        $iSelectedInstallmentPlanProfileId,
        $iNumberOfInstallments,
        $sIBAN,
        $sBIC
    ) {
        $service = oxNew(CreateContractService::class, $afterpayCheckoutId);
        return $service->createContract(
            'afterpayinstallment',
            $sIBAN,
            $sBIC,
            $iSelectedInstallmentPlanProfileId,
            $iNumberOfInstallments
        );
    }
}
