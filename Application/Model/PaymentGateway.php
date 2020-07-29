<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model;

use Arvato\AfterpayModule\Core\Exception\PaymentException;
use Arvato\AfterpayModule\Core\ValidateBankAccountService;
use Exception;
use OxidEsales\Eshop\Core\Registry;
use Arvato\AfterpayModule\Core\AuthorizePaymentService;
use Arvato\AfterpayModule\Core\AvailablePaymentMethodsService;

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
     * @param double $amount Goods amount
     * @param Order &$order User ordering object
     *
     * @return bool Success, false on error
     * @extend executePayment
     */
    public function executePayment($amount, &$order) //Superfluous ampersand to match parents definition
    {

        try {
            // Reset some session data to be generated during upcoming process
            $this->resetArvatoSessionVars();

            // If we are not in an afterpay payment (how did we get here in the first place?)
            // just go with the default payment gateway.
            if (!$order->isAfterpayPaymentType()) {
                return $this->dereferToOtherPaymentProviders($amount, $order);
            }

            if (!$order->isAfterpayDebitNote() && !$order->isAfterpayInvoice() && !$order->isAfterpayInstallment()) {
                return $this->dereferToOtherPaymentProviders($amount, $order);
            }

            $result = $this->dereferToPaymentHandling($order);
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
     * @param $order
     *
     * @return bool False on Error
     * @throws PaymentException
     */
    protected function dereferToPaymentHandling($order)
    {

        // Get Order No. so it can be used as request identifier.
        // That might leave a hole in the order numbering if the payment gets rejected.
        $order->setNumber();

        $success = true;

        if ($order->isAfterpayDebitNote()) {
            oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)
                ->saveApiKeyToSession(false);
            // Call on DebitNote Only
            $success = $this->handleDebitNote($order);
        }

        if ($order->isAfterpayInstallment()) {
            oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)
                ->saveApiKeyToSession(true);
            // Call on DebitNote Only
            $success = $this->handleInstallment($order);
        }
        if ($order->isAfterpayInvoice()) {
            oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)
                ->saveApiKeyToSession(false);
        }

        if ($success) {
            // Call on every afterpay payment
            $success = $this->handleInvoice($order);
        }

        return $success;
    }

    /**
     * @param $amount
     * @param $order
     *
     * @return bool Success
     * @codeCoverageIgnore Mocking helper
     */
    protected function dereferToOtherPaymentProviders($amount, $order)
    {
        $defaultPaymentGateway = oxNew(\OxidEsales\Eshop\Application\Model\PaymentGateway::class);
        return $defaultPaymentGateway->executePayment($amount, $order);
    }

    /**
     * @param $order
     *
     * @return bool Success False on Error
     */
    protected function handleInvoice($order)
    {

        $service = $this->getAuthorizePaymentService($order);
        $response = $service->authorizePayment($order);

        $this->_sLastError = null;
        if ($response != 'Accepted') {
            $order->resetNumber();
            $this->_sLastError = $service->getErrorMessages();
            $this->_iLastErrorNo = $service->getLastErrorNo();
            return false;
        }

        $aporder = oxNew(AfterpayOrder::class, $order);
        $aporder->fillBySession(Registry::getSession());
        $aporder->save();

        return true;
    }

    /**
     * @param Order $order
     *
     * @return string $contractId False on Error
     */
    public function handleDebitNote($order)
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

        if (!$this->getAvailablePaymentMethodsService($order)->isDirectDebitAvailable()) {
            return false;
        }

        $this->getSession()->setVariable('arvatoAfterpayIBAN', $apdebitbankaccount);
        $this->getSession()->setVariable('arvatoAfterpayBIC', $apdebitbankcode);


        return true;
    }

    /**
     * @param Order $order
     *
     * @return string $contractId False on error
     */
    public function handleInstallment($order)
    {

        // Bank account ok? - Redundant to former call but makes process tamper-proof

        list($BIC, $IBAN) = $this->gatherIBANandBIC();

        if (!$IBAN || !$BIC || !$this->getValidateBankAccountService()->isValid($IBAN, $BIC)) {
            return false;
        }

        $this->getSession()->setVariable('arvatoAfterpayIBAN', $IBAN);
        $this->getSession()->setVariable('arvatoAfterpayBIC', $BIC);

        // Installment profile selected?

        $dynValue = $this->getSession()->getVariable('dynvalue');

        if (!isset($dynValue['afterpayInstallmentProfileId'])) {
            $this->_sLastError = 'NO INSTALLMENT PLAN SELECTED';
            return false;
        }

        $selectedInstallmentPlanProfileId = $dynValue['afterpayInstallmentProfileId'];

        // Is selected installment plan available?

        $availablePaymentMethodsService = $this->getAvailablePaymentMethodsService($order);

        if (
            !($availablePaymentMethodsService
            ->isSpecificInstallmentAvailable($selectedInstallmentPlanProfileId))
        ) {
            $this->_iLastErrorNo = $availablePaymentMethodsService->getLastErrorNo();
            $this->_sLastError = $availablePaymentMethodsService->getErrorMessages();
            return false;
        }

        return true;
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
    protected function getAvailablePaymentMethodsService($order)
    {
        $session = Registry::getSession();
        $language = Registry::getLang();
        return oxNew(AvailablePaymentMethodsService::class, $session, $language, $order);
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
        $session = Registry::getSession();
        $session->deleteVariable('arvatoAfterpayCheckoutId');
        $session->deleteVariable('arvatoAfterpayContractId');
        $session->deleteVariable('arvatoAfterpayIBAN');
        $session->deleteVariable('arvatoAfterpayBIC');
        $session->deleteVariable('arvatoAfterpayApiKey');
        $session->deleteVariable('arvatoAfterpayCustomerFacingMessage');
    }

    /**
     * @return array
     */
    protected function gatherIBANandBIC()
    {
        $IBAN = $BIC = null;

        foreach ($this->_oPaymentInfo->_aDynValues as $dynValue) {
            if ('apinstallmentbankaccount' === $dynValue->name) {
                $IBAN = $dynValue->value;
            }
            if ('apinstallmentbankcode' === $dynValue->name) {
                $BIC = $dynValue->value;
            }
        }
        return [$BIC, $IBAN];
    }
}
