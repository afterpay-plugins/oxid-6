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
 * Class OrderController : Extends order controller with AfterPay service call.
 *
 * @extends OrderController
 *
 *  Naming of the "*Order"-Classes:
 *    - Order:           Extension of order - model
 *    - OrderController: Extension of order - view <-- THIS FILE
 *    - AfterpayOrder:   New model as seen in db table afterpayorder
 */
class OrderController extends OrderController_parent
{

    /**
     * Send this code as result of paymentGateway::getLastErrorNo()
     * to redirect User to address page
     */
    const ARVATO_ORDER_STATE_CHECKADDRESS = 41470; // Completely random code

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
        $smarty = Registry::getUtilsView()->getSmarty();

        // Gather and update currently selected installment plan profile id ...
        $selectedInstallmentPlanProfileIdInSession = $this->updateSelectedInstallmentPlanProfileIdInSession(true);
        $smarty->assign('afterpayInstallmentProfileId', $selectedInstallmentPlanProfileIdInSession);

        // ... and available installment plans...
        $availableInstallmentPlans = $this->getAvailableInstallmentPlans();
        $smarty->assign('aAvailableAfterpayInstallmentPlans', $availableInstallmentPlans);

        // ... make sure we redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected ...
        $this->redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected($availableInstallmentPlans);

        // Ok, here we either have not selected an installment plan, or there are installment plans available.
        if ($availableInstallmentPlans) {
            $this->smartyAssignAvailableInstallmentPlans(
                $availableInstallmentPlans,
                $selectedInstallmentPlanProfileIdInSession
            );
        }
    }

    /**
     * @return bool|AvailableInstallmentPlansResponseEntity
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
        $availableInstallmentPlans = $objAvailableInstallmentPlans->getAvailableInstallmentPlans();


        if (is_array($availableInstallmentPlans) && count($availableInstallmentPlans)) {
            foreach ($availableInstallmentPlans  as &$plan) {
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
     * Update InstallmentPlan Id in session, based upon resquest param.
     * If Session-Stored Profiole ID would be empty, enforce "1".
     *
     * @param bool $returnNewProfileId false for frontend form call, set true for in-code-Usage
     *
     * @return int afterpayInstallmentProfileId
     */
    public function updateSelectedInstallmentPlanProfileIdInSession($returnNewProfileId = false)
    {
        $dynValue = $this->getSession()->getVariable('dynvalue');

        if ($newInstallmentId = $this->getRequestParameter('afterpayInstallmentProfileId')) {
            // Set to request value
            $dynValue['afterpayInstallmentProfileId'] = $newInstallmentId;
        }

        if (!$dynValue['afterpayInstallmentProfileId']) {
            // If empty, set to 1
            $dynValue['afterpayInstallmentProfileId'] = 1;
        }

        $this->getSession()->setVariable('dynvalue', $dynValue);

        if ($returnNewProfileId) {
            return $dynValue['afterpayInstallmentProfileId'];
        }
        return null;
    }

    /**
     * redirect To Payment If No Installment Plan Available Although Selected
     *
     * @param $availableInstallmentPlans
     */
    protected function redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected($availableInstallmentPlans)
    {

        $isInstallmentPlanSelected = 'afterpayinstallment' == $this->getSession()->getVariable('paymentid');

        if ($isInstallmentPlanSelected && (!is_array($availableInstallmentPlans) || !count($availableInstallmentPlans))) {
            // redirecting to payment step on error ..
            $this->redirectToPayment();
        }
    }

    /**
     * @param $availableInstallmentPlans
     * @param $selectedInstallmentPlanProfileIdInSession
     */
    public function smartyAssignAvailableInstallmentPlans(
        $availableInstallmentPlans,
        $selectedInstallmentPlanProfileIdInSession
    ) {

        $smarty = Registry::getUtilsView()->getSmarty();

        // Assign installment plan formatting ...
        $availableInstallmentPlanFormattings = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity::class)->getAvailableInstallmentPlanFormattings();
        $smarty->assign('aAvailableAfterpayInstallmentPlanFormattings', $availableInstallmentPlanFormattings);

        // ... and the URL to the legal documents, based upon current plan choice ...
        $selectedInstallmentPlan = isset($availableInstallmentPlans[$selectedInstallmentPlanProfileIdInSession])
            ?
            $availableInstallmentPlans[$selectedInstallmentPlanProfileIdInSession]
            :
            reset( $availableInstallmentPlans);

        $smarty->assign('afterpayReadMoreLink', $selectedInstallmentPlan->readMore);

        // ... and price info for interests ...
        $smarty->assign('afterpayTotalInterestAmount', $selectedInstallmentPlan->totalInterestAmount);
        $smarty->assign('afterpayNewGrandTotal', $selectedInstallmentPlan->totalAmount);

        // ... and whether we need to show SECCI
        $secciPriceMet = 200 <= $this->getSession()->getBasket()->getPrice()->getBruttoPrice();
        $secciAnnualRateMet = 0 < $selectedInstallmentPlan->effectiveInterestRate;
        $showSecci = $secciPriceMet && $secciAnnualRateMet;

        $smarty->assign('afterpayShowSecci', $showSecci);
    }

    /**
     * self::ARVATO_ORDER_STATE_CHECKADDRESS makes user check his address
     *
     * @param int $success status code
     *
     * @return  string  $sNextStep  partial parameter url for next step
     * @phpcs:disable
     */
    protected function _getNextStep($success)
    {
        if ($success === self::ARVATO_ORDER_STATE_CHECKADDRESS) {
            return 'user?wecorrectedyouraddress=1';
        } elseif (is_string($success) && 10 < strlen($success)) {
            $session = Registry::getSession();
            $session->setVariable('arvatoAfterpayCustomerFacingMessage', $success);
            return 'user?cfm=1';
        }

        return parent::_getNextStep($success);
    }

    /**
     * Validates whether necessary terms and conditions checkboxes were checked.
     * @codeCoverageIgnore
     * @return bool
     * @phpcs:disable
     */
    public function _validateTermsAndConditions()
    {
        $paymentId = $this->getSession()->getVariable('paymentid');
        $isAfterpay = (false !== strpos($paymentId, 'afterpay'));

        if ($isAfterpay && !$this->getRequestParameter('ord_afterpay_agb')) {
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
     * @param $paramName
     * @return mixed
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function getRequestParameter($paramName)
    {
        return Registry::get(Request::class)->getRequestEscapedParameter($paramName);
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
