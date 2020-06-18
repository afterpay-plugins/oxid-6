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
 * Class OrderController : Extends order controller with AfterPay service call.
 *
 * @extends order
 *
 *  Naming of the "*Order"-Classes:
 *    - ArvatoAfterpayOxOrder: Exctension of oxOrder - model
 *    - OrderController: Exctension of order - view
 *    - AfterpayOrder: New model as seen in db table afterpayorder
 */
class OrderController extends OrderController_parent
{

    /**
     * Send this code as result of paymentGateway::getLastErrorNo()
     * to redirect User to address page
     */
    public const ARVATO_ORDER_STATE_CHECKADDRESS = 41470; // Completely random code

    /**
     * @var string[] Error messages from the AfterPay service.
     */
    protected $_errorMessages;

    public function render()
    {
        if ('afterpayinstallment' == $this->getSession()->getVariable('paymentid')) {
            $this->renderSelectedInstallmentPlan();
        }
        return $this->parentRender();
    }

    public function getOrderStateCheckAddressConstant()
    {
        return self::ARVATO_ORDER_STATE_CHECKADDRESS;
    }

    /**
     * render additinal informaiton if installment is selected payment id
     */
    public function renderSelectedInstallmentPlan()
    {
        $oSmarty = Registry::getUtilsView()->getSmarty();

        // Gather and update currently selected installment plan profile id ...
        $selectedInstallmentPlanProfileIdInSession = $this->updateSelectedInstallmentPlanProfileIdInSession(true);
        $oSmarty->assign('afterpayInstallmentProfileId', $selectedInstallmentPlanProfileIdInSession);

        // ... and available installment plans...
        $aAvailableInstallmentPlans = $this->getAvailableInstallmentPlans();
        $oSmarty->assign('aAvailableAfterpayInstallmentPlans', $aAvailableInstallmentPlans);

        // ... make sure we redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected ...
        $this->redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected($aAvailableInstallmentPlans);

        // Ok, here we either have not selected an installment plan, or there are installment plans available.
        if ($aAvailableInstallmentPlans) {
            $this->smartyAssignAvailableInstallmentPlans(
                $aAvailableInstallmentPlans,
                $selectedInstallmentPlanProfileIdInSession
            );
        }
    }

    /**
     * @return bool|AvailableInstallmentPlansResponseEntity
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
     * Update InstallmentPlan Id in session, based upon resquest param.
     * If Session-Stored Profiole ID would be empty, enforce "1".
     *
     * @param bool $bReturnNewProfileId false for frontend form call, set true for in-code-Usage
     *
     * @return int afterpayInstallmentProfileId
     */
    public function updateSelectedInstallmentPlanProfileIdInSession($bReturnNewProfileId = false)
    {
        $aDynValue = $this->getSession()->getVariable('dynvalue');

        if ($newInstallmentId = $this->getRequestParameter('afterpayInstallmentProfileId')) {
            // Set to request value
            $aDynValue['afterpayInstallmentProfileId'] = $newInstallmentId;
        }

        if (!$aDynValue['afterpayInstallmentProfileId']) {
            // If empty, set to 1
            $aDynValue['afterpayInstallmentProfileId'] = 1;
        }

        $this->getSession()->setVariable('dynvalue', $aDynValue);

        if ($bReturnNewProfileId) {
            return $aDynValue['afterpayInstallmentProfileId'];
        }
        return null;
    }

    /**
     * redirect To Payment If No Installment Plan Available Although Selected
     *
     * @param $aAvailableInstallmentPlans
     */
    protected function redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected($aAvailableInstallmentPlans)
    {

        $bIsInstallmentPlanSelected = 'afterpayinstallment' == $this->getSession()->getVariable('paymentid');

        if ($bIsInstallmentPlanSelected && (!is_array($aAvailableInstallmentPlans) || !count($aAvailableInstallmentPlans))) {
            // redirecting to payment step on error ..
            $this->redirectToPayment();
        }
    }

    /**
     * @param $aAvailableInstallmentPlans
     * @param $selectedInstallmentPlanProfileIdInSession
     */
    public function smartyAssignAvailableInstallmentPlans(
        $aAvailableInstallmentPlans,
        $selectedInstallmentPlanProfileIdInSession
    ) {

        $oSmarty = Registry::getUtilsView()->getSmarty();

        // Assign installment plan formatting ...
        $aAvailableInstallmentPlanFormattings = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity::class)->getAvailableInstallmentPlanFormattings();
        $oSmarty->assign('aAvailableAfterpayInstallmentPlanFormattings', $aAvailableInstallmentPlanFormattings);

        // ... and the URL to the legal documents, based upon current plan choice ...
        $oSelectedInstallmentPlan = isset($aAvailableInstallmentPlans[$selectedInstallmentPlanProfileIdInSession])
            ?
            $aAvailableInstallmentPlans[$selectedInstallmentPlanProfileIdInSession]
            :
            reset($aAvailableInstallmentPlans);

        $oSmarty->assign('afterpayReadMoreLink', $oSelectedInstallmentPlan->readMore);

        // ... and price info for interests ...
        $oSmarty->assign('afterpayTotalInterestAmount', $oSelectedInstallmentPlan->totalInterestAmount);
        $oSmarty->assign('afterpayNewGrandTotal', $oSelectedInstallmentPlan->totalAmount);

        // ... and whether we need to show SECCI
        $bSecciPriceMet = 200 <= $this->getSession()->getBasket()->getPrice()->getBruttoPrice();
        $bSecciAnnualRateMet = 0 < $oSelectedInstallmentPlan->effectiveInterestRate;
        $bShowSecci = $bSecciPriceMet && $bSecciAnnualRateMet;

        $oSmarty->assign('afterpayShowSecci', $bShowSecci);
    }

    /**
     * self::ARVATO_ORDER_STATE_CHECKADDRESS makes user check his address
     *
     * @param int $iSuccess status code
     *
     * @return  string  $sNextStep  partial parameter url for next step
     * @phpcs:disable
     */
    protected function _getNextStep($iSuccess)
    {
        if ($iSuccess === self::ARVATO_ORDER_STATE_CHECKADDRESS) {
            return 'user?wecorrectedyouraddress=1';
        } elseif (is_string($iSuccess) && 10 < strlen($iSuccess)) {
            $oxSession = Registry::getSession();
            $oxSession->setVariable('arvatoAfterpayCustomerFacingMessage', $iSuccess);
            return 'user?cfm=1';
        }

        return parent::_getNextStep($iSuccess);
    }

    /**
     * Validates whether necessary terms and conditions checkboxes were checked.
     * @codeCoverageIgnore
     * @return bool
     * @phpcs:disable
     */
    public function _validateTermsAndConditions()
    {
        $oConfig = Registry::getConfig();
        $sPaymentId = $this->getSession()->getVariable('paymentid');
        $bIsAfterpay = (false !== strpos($sPaymentId, 'afterpay'));

        if ($bIsAfterpay && !$oConfig->getRequestParameter('ord_afterpay_agb')) {
            return false;
        }

        return parent::_validateTermsAndConditions();
    }


    /////////////////////////////////////////////////////
    // UNIT TEST HELPERS - all uncovered
    // @codeCoverageIgnoreStart

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
     * @return AvailableInstallmentPlansService
     */
    protected function getAvailableInstallmentPlansService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\AvailableInstallmentPlansService::class);
    }

    /**
     * @return mixed
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function getRequestParameter($sParamName)
    {
        return Registry::getConfig()->getRequestParameter($sParamName);
    }

    /**
     * Gets the error messages form the AfterPay service.
     *
     * @return string[]
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    public function getAfterpayErrorMessages()
    {
        return $this->_errorMessages;
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function redirectToPayment()
    {
        Registry::getUtils()->redirect(Registry::getConfig()->getShopCurrentURL() . '&cl=payment', true, 302);
    }

    // @codeCoverageIgnoreEnd
}
