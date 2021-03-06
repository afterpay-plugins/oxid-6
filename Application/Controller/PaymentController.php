<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Controller;

use Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity;
use Arvato\AfterpayModule\Core\AvailableInstallmentPlansService;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

/**
 * Class PaymentController : Extends payment controller with AfterPay validation call.
 *
 * @extends Payment
 *
 */
class PaymentController extends PaymentController_parent
{

    const ARVATO_ORDER_STATE_SELECTINSTALLMENT = -13337;

    /**
     * @var string[] Error messages from the AfterPay service.
     */
    protected $_errorMessages;

    public function render()
    {
        $smarty = Registry::getUtilsView()->getSmarty();

        // Gather available installment plans...
        $availableInstallmentPlans = $this->getAvailableInstallmentPlans();
        $smarty->assign('aAvailableAfterpayInstallmentPlans', $availableInstallmentPlans);

        // ... their formatting ...
        $availableInstallmentPlanFormattings = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity::class)->getAvailableInstallmentPlanFormattings();
        $smarty->assign('aAvailableAfterpayInstallmentPlanFormattings', $availableInstallmentPlanFormattings);

        // ... and currently selected installment plan (if there is a selected one)
        $selectedInstallmentPlanProfileIdInSession = $this->updateSelectedInstallmentPlanProfileIdInSession();
        $smarty->assign('afterpayInstallmentProfileId', $selectedInstallmentPlanProfileIdInSession);

        // Assign required fields
        $this->assignRequiredDynValue();

        // Finally resume oxids handling
        return $this->parentRender();
    }

    public function getOrderStateSelectInstallmentConstant()
    {
        return self::ARVATO_ORDER_STATE_SELECTINSTALLMENT;
    }

    public function assignRequiredDynValue()
    {

        if (!$this->getUser()) {
            return null;
        }

        $smarty = Registry::getUtilsView()->getSmarty();
        $requirements = ['Invoice' => [], 'Debit' => [], 'Installments' => []];

        // SSN

        $oxcmp_user = $this->getUser();
        $alreadyHaveBirthdate = false !== strpos($oxcmp_user->oxuser__oxbirthdate->value, '19');
        $alreadyHavePhone = $oxcmp_user->oxuser__oxfon->value || $oxcmp_user->oxuser__oxmob->value || $oxcmp_user->oxuser__oxprivfon->value;

        foreach (array_keys($requirements) as $payment) {
            $requirements[$payment]['SSN'] =
                Registry::getConfig()->getConfigParam('arvatoAfterpay' . $payment . 'RequiresSSN');

            $requirements[$payment]['Birthdate'] =
                (!$alreadyHaveBirthdate && Registry::getConfig()->getConfigParam('arvatoAfterpay' . $payment . 'RequiresBirthdate'));

            $requirements[$payment]['Fon'] =
                (!$alreadyHavePhone && Registry::getConfig()->getConfigParam('arvatoAfterpay' . $payment . 'RequiresBirthdate'));
        }

        $smarty->assign('aAfterpayRequiredFields', $requirements);

        // Return value solely for unit testing
        return $requirements;
    }

    /**
     * Validates afterpay payment credentials.
     * Returns null if problems on validating occured. If everything
     * is OK - returns parent::validate()
     *
     * @return int Error Code
     */
    public function validatePayment()
    {
        $parentReturn = $this->parentValidatePayment();

        $paymentId = $this->getRequestOrSessionParameter('paymentid');
        $dynValue = $this->getRequestOrSessionParameter('dynvalue');

        $error = 0;

        if ($paymentId == "afterpaydebitnote") {
            $error = $this->validateDebitNote($dynValue);
        }

        if ($paymentId == "afterpayinstallment") {
            $this->validateAndSaveSelectedInstallmentPforileId($dynValue);
            $error = $this->validateInstallment($dynValue);
        }

        $this->_sPaymentError = $error;

        // Everything null on error, return parent::return if everything is ok.
        return $error ? null : $parentReturn;
    }

    /**
     * @return bool|AvailableInstallmentPlansResponseEntity[]
     */
    public function getAvailableInstallmentPlans()
    {

        $amount = $this->getSession()->getBasket()->getPrice()->getBruttoPrice();

        if (!$amount) {
            // Session lost.
            return false;
        }

        $availableInstallmentPlansService = $this->getAvailableInstallmentPlansService();
        $objAvailableInstallmentPlans = $availableInstallmentPlansService->getAvailableInstallmentPlans($amount);
        $availableInstallmentPlans  = $objAvailableInstallmentPlans->getAvailableInstallmentPlans();

        if (is_array($availableInstallmentPlans) && count($availableInstallmentPlans)) {
            foreach ($availableInstallmentPlans as &$plan) {
                unset($plan->effectiveAnnualPercentageRate);
            }

            // Make Array keys equal profile Id
            $availableInstallmentPlansWithProfileIdAsKey = [];

            foreach ($availableInstallmentPlans as &$plan) {
                $availableInstallmentPlansWithProfileIdAsKey[$plan->installmentProfileNumber] = $plan;
            }

            return $availableInstallmentPlansWithProfileIdAsKey;
        }

        return null;
    }

    /**
     * Update InstallmentPlan Id
     *
     * @return int afterpayInstallmentProfileId
     */
    public function updateSelectedInstallmentPlanProfileIdInSession()
    {
        $orderController = oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class);
        return $orderController->updateSelectedInstallmentPlanProfileIdInSession(true);
    }

    /**
     * Validates Debit note for required parameters
     *
     * @param $dynValue
     *
     * @return int Error
     */
    protected function validateDebitNote($dynValue)
    {
        if (
            !isset($dynValue['apdebitbankaccount'])
            || !isset($dynValue['apdebitbankcode'])
            || !$dynValue['apdebitbankaccount']
            || !$dynValue['apdebitbankcode']
        ) {
            return 1; //Complete fields correctly
        }
        return 0;
    }

    /**
     * Validates Installment for required parameters
     *
     * @param $dynValue
     *
     * @return int Error
     */
    protected function validateInstallment($dynValue)
    {
        if (
            !isset($dynValue['apinstallmentbankaccount'])
            || !isset($dynValue['apinstallmentbankcode'])
            || !$dynValue['apinstallmentbankaccount']
            || !$dynValue['apinstallmentbankcode']
        ) {
            return 1; //Complete fields correctly
        }
        return 0;
    }

    /**
     * @param $dynValue
     *
     * @return int
     */
    protected function validateAndSaveSelectedInstallmentPforileId($dynValue)
    {
        if (isset($dynValue['afterpayInstallmentProfileId']) && $dynValue['afterpayInstallmentProfileId']) {
            $this->getSession()->setVariable(
                'arvatoAfterpayInstallmentProfileId',
                $dynValue['afterpayInstallmentProfileId']
            );
            return 0;
        }
        return self::ARVATO_ORDER_STATE_SELECTINSTALLMENT; //Select installment plan profile id
    }


    /////////////////////////////////////////////////////
    // UNIT TEST HELPERS - all uncovered
    // @codeCoverageIgnoreStart

    /**
     * Gets the error messages form the AfterPay service.
     *
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return string[]
     */
    public function getAfterpayErrorMessages()
    {
        return $this->_errorMessages;
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return mixed
     */
    protected function parentRender()
    {
        return parent::render();
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return mixed
     */
    protected function parentValidatePayment()
    {
        return parent::validatePayment();
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return AvailableInstallmentPlansService|object
     */
    protected function getAvailableInstallmentPlansService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\AvailableInstallmentPlansService::class);
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @param $paramName
     * @return mixed
     */
    protected function getRequestOrSessionParameter($paramName)
    {
        $requestReturn = Registry::get(Request::class)->getRequestEscapedParameter($paramName);
        return $requestReturn ?: $this->getSession()->getVariable($paramName);
    }
}
