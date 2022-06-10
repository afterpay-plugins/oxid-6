<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Controller;

use Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity;
use Arvato\AfterpayModule\Core\AfterpayIdStorage;
use Arvato\AfterpayModule\Core\AvailableInstallmentPlansService;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\DatabaseProvider;
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
        $this->_setTCLink();
        $this->_setPrivacyLink();

        return $this->parentRender();
    }

    /**
     * _setTCLink
     * -----------------------------------------------------------------------------------------------------------------
     *  set in smarty variable T&C link per payment and country
     *
     */
    protected function _setTCLink()
    {
        /** @var User $user */
        $user = $this->getUser();
        $links = Registry::get(AfterpayIdStorage::class)->getTCPrivacyLinks();
        $lang = $this->getActiveLangAbbr();

        // if Austria
        if ($user->oxuser__oxcountryid->value == "a7c40f6320aeb2ec2.72885259") {
            $country = 'at';
        } else {
            $country = 'de';
        }

        switch ($this->getSession()->getVariable('paymentid')) {
            case "afterpayinvoice":
                $paymentId = "invoice";
                break;
            case "afterpayinstallment":
                $paymentId = "fix_installments";
                break;
            case "afterpaydebitnote":
                $paymentId = "direct_debit";
                break;
        }
        $AGBLink = str_replace('##LANGCOUNTRY##',$lang.'_'.$country,$links['TC']);
        $AGBLink = str_replace('##PAYMENT##', $paymentId, $AGBLink);
        if ($merchantID = Registry::getConfig()->getConfigParam('arvatoAfterpayHorizonID'.$user->oxuser__oxcountryid->value)) {
            $AGBLink = str_replace('##MERCHANT##', $merchantID, $AGBLink);
        } else {
            $AGBLink = str_replace('##MERCHANT##', 'muster-merchant', $AGBLink);
        }

        $smarty = Registry::getUtilsView()->getSmarty();
        $smarty->assign('AGBLink', $AGBLink);
    }

    /**
     * _setPrivacyLink
     * -----------------------------------------------------------------------------------------------------------------
     * set in smarty variable privacy link per payment and country
     *
     */
    protected function _setPrivacyLink()
    {
        $smarty = Registry::getUtilsView()->getSmarty();
        $links = Registry::get(AfterpayIdStorage::class)->getTCPrivacyLinks();
        $lang = $this->getActiveLangAbbr();

        $user = $this->getUser();

        switch ($user->oxuser__oxcountryid->value) {
            case "a7c40f631fc920687.20179984":      //Germany
                $country = 'de';
                break;
            case "a7c40f6320aeb2ec2.72885259":      //Austria
                $country = 'at';
                break;
            case "a7c40f632cdd63c52.64272623":      //Netherlands
                $country = 'nl';
                break;
            case "a7c40f632e04633c9.47194042":      //Belgium
                $country = 'be';
                break;
            default:
                $country = 'de';
                $lang = 'en';
                break;
        }

        $privacyLink = str_replace('##LANGCOUNTRY##',$lang.'_'.$country,$links['privacy']);
        $privacyLink = str_replace('##MERCHANT##',Registry::getConfig()->getConfigParam('arvatoAfterpayHorizonID'.$user->getActiveCountry()),$privacyLink);

        $smarty->assign('PrivacyLink', $privacyLink);
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
    protected function _validateTermsAndConditions()
    {
        $paymentId = $this->getSession()->getVariable('paymentid');
        $isAfterpay = (false !== strpos($paymentId, 'afterpay'));
        $ouser = $this->getUser();
        $configString = "arvatoAfterpayInvoiceRequiresTC" . $ouser->oxuser__oxcountryid->value;
        $agb = Registry::getConfig()->getConfigParam($configString);

        if ($agb) {
            if ($isAfterpay && !$this->getRequestParameter('ord_afterpay_agb')) {
                return false;
            }
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

    /**
     * getActiveLocale
     * ----------------------------------------------------------------------------------------------------------------
     * gets the locale for the current locale (needed for link in tpl)
     *
     * @return string
     */
    public function getActiveLocale()
    {
        $user = $this->getUser();
        $locale = "de_de";
        if ($user) {
            $oLang = \OxidEsales\Eshop\Core\Registry::getLang();
            $sLang = $oLang->getLanguageAbbr($oLang->getTplLanguage());
            $sql = "SELECT oxisoalpha2 FROM oxcountry where oxid ='" . $user->oxuser__oxcountryid->value . "'";
            $countryiso = DatabaseProvider::getDb()->getOne($sql);

            $locale = strtolower($sLang . "_" . $countryiso);
        }

        return $locale;
    }

    /**
     * getActiveLocale
     * ----------------------------------------------------------------------------------------------------------------
     * gets the locale for the current locale (needed for link in tpl)
     *
     * @return string
     */
    public function getMerchantId()
    {
        /** @var User $user */
        $user = $this->getUser();
        $merchantID = Registry::getConfig()->getConfigParam('arvatoAfterpayHorizonID' . $user->oxuser__oxcountryid->value);

        if ($merchantID) {
            return $merchantID;
        }
        return "muster-merchant";
    }
}
