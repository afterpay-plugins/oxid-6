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

namespace Arvato\AfterpayModule\Application\Controller;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class PaymentController : Extends payment controller with AfterPay validation call.
 *
 * @extends Payment
 *
 */
class PaymentController extends PaymentController_parent
{

    public const ARVATO_ORDER_STATE_SELECTINSTALLMENT = -13337;

    /**
     * @var string[] Error messages from the AfterPay service.
     */
    protected $_errorMessages;

    public function render()
    {
        $oSmarty = Registry::getUtilsView()->getSmarty();

        // Gather available installment plans...
        $aAvailableInstallmentPlans = $this->getAvailableInstallmentPlans();
        $oSmarty->assign('aAvailableAfterpayInstallmentPlans', $aAvailableInstallmentPlans);

        // ... their formatting ...
        $aAvailableInstallmentPlanFormattings = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity::class)->getAvailableInstallmentPlanFormattings();
        $oSmarty->assign('aAvailableAfterpayInstallmentPlanFormattings', $aAvailableInstallmentPlanFormattings);

        // ... and currently selected installment plan (if there is a selected one)
        $selectedInstallmentPlanProfileIdInSession = $this->updateSelectedInstallmentPlanProfileIdInSession();
        $oSmarty->assign('afterpayInstallmentProfileId', $selectedInstallmentPlanProfileIdInSession);

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

        $oSmarty = Registry::getUtilsView()->getSmarty();
        $aRequirements = ['Invoice' => [], 'Debit' => [], 'Installments' => []];

        // SSN

        $oxcmp_user = $this->getUser();
        $bAlreadyHaveBirthdate = false !== strpos($oxcmp_user->oxuser__oxbirthdate->value, '19');
        $bAlreadyHavePhone = $oxcmp_user->oxuser__oxfon->value || $oxcmp_user->oxuser__oxmob->value || $oxcmp_user->oxuser__oxprivfon->value;

        foreach (array_keys($aRequirements) as $sPayment) {
            $aRequirements[$sPayment]['SSN'] =
                Registry::getConfig()->getConfigParam('arvatoAfterpay' . $sPayment . 'RequiresSSN');

            $aRequirements[$sPayment]['Birthdate'] =
                (!$bAlreadyHaveBirthdate && Registry::getConfig()->getConfigParam('arvatoAfterpay' . $sPayment . 'RequiresBirthdate'));

            $aRequirements[$sPayment]['Fon'] =
                (!$bAlreadyHavePhone && Registry::getConfig()->getConfigParam('arvatoAfterpay' . $sPayment . 'RequiresBirthdate'));
        }

        $oSmarty->assign('aAfterpayRequiredFields', $aRequirements);

        // Return value solely for unit testing
        return $aRequirements;
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

        $sPaymentId = $this->getRequestOrSessionParameter('paymentid');
        $aDynvalue = $this->getRequestOrSessionParameter('dynvalue');

        $iError = 0;

        if ($sPaymentId == "afterpaydebitnote") {
            $iError = $this->validateDebitNote($aDynvalue);
        }

        if ($sPaymentId == "afterpayinstallment") {
            $this->validateAndSaveSelectedInstallmentPforileId($aDynvalue);
            $iError = $this->validateInstallment($aDynvalue);
        }

        $this->_sPaymentError = $iError;

        // Everything null on error, return parent::return if everything is ok.
        return $iError ? null : $parentReturn;
    }

    /**
     * @return bool|AvailableInstallmentPlansResponseEntity[]
     */
    public function getAvailableInstallmentPlans()
    {

        $dAmount = $this->getSession()->getBasket()->getPrice()->getBruttoPrice();

        if (!$dAmount) {
            // Session lost.
            return false;
        }

        $availableInstallmentPlansService = $this->getAvailableInstallmentPlansService();
        $oAvailableInstallmentPlans = $availableInstallmentPlansService->getAvailableInstallmentPlans($dAmount);
        $aAvailableInstallmentPlans = $oAvailableInstallmentPlans->getAvailableInstallmentPlans();

        if (is_array($aAvailableInstallmentPlans) && count($aAvailableInstallmentPlans)) {
            foreach ($aAvailableInstallmentPlans as &$plan) {
                unset($plan->effectiveAnnualPercentageRate);
            }

            // Make Array keys equal profile Id
            $availableInstallmentPlansWithProfileIdAsKey = [];

            foreach ($aAvailableInstallmentPlans as &$plan) {
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
        $OrderController = oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class);
        return $OrderController->updateSelectedInstallmentPlanProfileIdInSession(true);
    }

    /**
     * Validates Debit note for required parameters
     *
     * @param $aDynvalue
     *
     * @return int Error
     */
    protected function validateDebitNote($aDynvalue)
    {
        if (
            !isset($aDynvalue['apdebitbankaccount'])
            || !isset($aDynvalue['apdebitbankcode'])
            || !$aDynvalue['apdebitbankaccount']
            || !$aDynvalue['apdebitbankcode']
        ) {
            return 1; //Complete fields correctly
        }
        return 0;
    }

    /**
     * Validates Installment for required parameters
     *
     * @param $aDynvalue
     *
     * @return int Error
     */
    protected function validateInstallment($aDynvalue)
    {
        if (
            !isset($aDynvalue['apinstallmentbankaccount'])
            || !isset($aDynvalue['apinstallmentbankcode'])
            || !$aDynvalue['apinstallmentbankaccount']
            || !$aDynvalue['apinstallmentbankcode']
        ) {
            return 1; //Complete fields correctly
        }
        return 0;
    }

    /**
     * @param $aDynvalue
     *
     * @return int
     */
    protected function validateAndSaveSelectedInstallmentPforileId($aDynvalue)
    {
        if (isset($aDynvalue['afterpayInstallmentProfileId']) && $aDynvalue['afterpayInstallmentProfileId']) {
            $this->getSession()->setVariable(
                'arvatoAfterpayInstallmentProfileId',
                $aDynvalue['afterpayInstallmentProfileId']
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
     * @return mixed
     */
    protected function getRequestOrSessionParameter($sParamName)
    {
        $requestReturn = Registry::getConfig()->getRequestParameter($sParamName);
        return $requestReturn ?: $this->getSession()->getVariable($sParamName);
    }
}
