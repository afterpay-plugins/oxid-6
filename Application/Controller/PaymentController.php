<?php

namespace Arvato\AfterpayModule\Application\Controller;

use Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity;
use Arvato\AfterpayModule\Core\AfterpayIdStorage;
use Arvato\AfterpayModule\Core\AvailableInstallmentPlansService;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class PaymentController : Extends payment controller with AfterPay validation call.
 *
 * @extends PaymentController
 *
 */
class PaymentController extends PaymentController_parent
{

    const ARVATO_ORDER_STATE_SELECTINSTALLMENT = -13337;

    /**
     * @var string[]
     */
    protected $map = [];

    /**
     * @var string[]
     */
    protected $userMapping = [
        'apsal'         => 'oxsal',
        'apfname'       => 'oxfname',
        'aplname'       => 'oxlname',
        'apbirthday'    => 'oxbirthdate',
        'apfon'         => 'oxfon',
        'apstreet'      => 'oxstreet',
        'apstreetnr'    => 'oxstreetnr',
        'apzip'         => 'oxzip',
        'apcity'        => 'oxcity',
    ];

    /**
     * @var string[] Error messages from the AfterPay service.
     */
    protected $_errorMessages;
    protected $arvatoInstallmentActive;

    /**
     * installmentPaymentActive
     * -----------------------------------------------------------------------------------------------------------------
     *
     */
    public function installmentPaymentActive()
    {
        if (isset($this->arvatoInstallmentActive)) {
            return $this->arvatoInstallmentActive;
        }

        try {
            $select = "SELECT oxactive FROM oxpayments WHERE oxid = ?";
            $this->arvatoInstallmentActive = (bool) DatabaseProvider::getDb()->getOne($select, ['afterpayinstallment']);
        } catch (\Exception $exception) {
            $this->arvatoInstallmentActive = false;
        }

        return $this->arvatoInstallmentActive;
    }

    public function getPaymentList()
    {
        $paymentList = parent::getPaymentList();
        if (!$this->allowAfterpayPayment()) {
            unset($paymentList["afterpayinvoice"]);
            unset($paymentList["afterpaydebitnote"]);
            unset($paymentList["afterpayinstallment"]);
        }

        return $paymentList;
    }

    public function render()
    {
        $smarty = Registry::getUtilsView()->getSmarty();

        // Gather available installment plans...
        $availableInstallmentPlans = $this->getAvailableInstallmentPlans();
        $smarty->assign('aAvailableAfterpayInstallmentPlans', $availableInstallmentPlans);

        // ... their formatting ...
        $availableInstallmentPlanFormattings = oxNew(AvailableInstallmentPlansResponseEntity::class)->getAvailableInstallmentPlanFormattings();
        $smarty->assign('aAvailableAfterpayInstallmentPlanFormattings', $availableInstallmentPlanFormattings);

        // ... and currently selected installment plan (if there is a selected one)
        $selectedInstallmentPlanProfileIdInSession = $this->updateSelectedInstallmentPlanProfileIdInSession();
        $smarty->assign('afterpayInstallmentProfileId', $selectedInstallmentPlanProfileIdInSession);

        // Assign required fields
        $this->assignRequiredDynValue();

        // Set required links
        $this->_setTCLink();
        $this->_setPrivacyLink();

        // Finally resume oxids handling
        return $this->parentRender();
    }

    public function getTrackingOption()
    {
        $possibleStates = ['inactive', 'mandatory', 'optional'];
        if (!in_array(Registry::getConfig()->getConfigParam( 'arvatoAfterpayProfileTrackingEnabled' ), $possibleStates)) {
            return 'inactive';
        }

        return Registry::getConfig()->getConfigParam( 'arvatoAfterpayProfileTrackingEnabled' );
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

        /** @var User $user */
        $user = $this->getUser();
        $availableFields = [];
        $availableFields["Birthdate"] = false !== strpos($user->oxuser__oxbirthdate->value, '19');
        $availableFields["Sal"] = $user->oxuser__oxsal->value;
        $availableFields["Phone"] = $user->oxuser__oxfon->value || $user->oxuser__oxmob->value || $user->oxuser__oxprivfon->value;
        $availableFields["Zip"] = $user->oxuser__oxzip->value;
        $availableFields["Street"] = $user->oxuser__oxstreet->value;
        $availableFields["StreetNR"] = $user->oxuser__oxstreetnr->value;
        $availableFields["FName"] = $user->oxuser__oxfname->value;
        $availableFields["LName"] = $user->oxuser__oxlname->value;
        $availableFields["City"] = $user->oxuser__oxcity->value;

        foreach (array_keys($requirements) as $payment) {
            $stringHelper = 'arvatoAfterpay' . $payment . 'Requires';

            $requirements[$payment]['Salutation'] =
                (!$availableFields["Sal"] && Registry::getConfig()->getConfigParam($stringHelper.'Salutation'. $user->getActiveCountry()));

            $requirements[$payment]['SSN'] =
                Registry::getConfig()->getConfigParam($stringHelper.'SSN'.$user->getActiveCountry());

            $requirements[$payment]['FName'] =
                (!$availableFields["FName"] && Registry::getConfig()->getConfigParam($stringHelper.'FirstName'. $user->getActiveCountry()));

            $requirements[$payment]['LName'] =
                (!$availableFields["LName"] && Registry::getConfig()->getConfigParam($stringHelper.'LastName'. $user->getActiveCountry()));

            $requirements[$payment]['Fon'] =
                (!$availableFields["Phone"] && Registry::getConfig()->getConfigParam($stringHelper.'Phone'. $user->getActiveCountry()));

            $requirements[$payment]['Birthdate'] = true; //NIK!

            $requirements[$payment]['Zip'] =
                (!$availableFields["Zip"] && Registry::getConfig()->getConfigParam($stringHelper.'Zip'. $user->getActiveCountry()));

            $requirements[$payment]['Street'] =
                (!$availableFields["Street"] && Registry::getConfig()->getConfigParam($stringHelper.'Street'. $user->getActiveCountry()));

            $requirements[$payment]['StreetNumber'] =
                (!$availableFields["StreetNR"] && Registry::getConfig()->getConfigParam($stringHelper.'StreetNumber'. $user->getActiveCountry()));

            $requirements[$payment]['City'] =
                (!$availableFields["City"] && Registry::getConfig()->getConfigParam($stringHelper.'City'. $user->getActiveCountry()));
        }

        $smarty->assign('aAfterpayRequiredFields', $requirements);
        $this->_assignMap();

        // Return value solely for unit testing
        return $requirements;
    }

    /**
     * _assignMap
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     */
    protected function _assignMap()
    {
        $this->map = [
            // dynvalue field => required field "name"
            'apsal'         => 'Salutation',
            'apssn'         => 'SSN',
            'apfname'       => 'FName',
            'aplname'       => 'LName',
            'apbirthday'    => 'Birthdate',
            'apfon'         => 'Fon',
            'apstreet'      => 'Street',
            'apstreetnr'    => 'StreetNumber',
            'apzip'         => 'Zip',
            'apcity'        => 'City',
        ];
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

        // return error directly, if the mandatory Tracking Checkbox not checked
        if ($this->getTrackingOption() == "mandatory" && !$this->getRequestOrSessionParameter('AfterPayTrackingEnabled')) {
            $error = 1;
        }

        if ($paymentId == "afterpaydebitnote" && !$error) {
            $error = $this->validateDebitNote($dynValue);
        }

        if ($paymentId == "afterpayinvoice" && !$error) {
            $error = $this->validateInvoice($dynValue);
        }

        if ($paymentId == "afterpayinstallment" && !$error) {
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
        if ($this->installmentPaymentActive()) {
            $amount = $this->getSession()->getBasket()->getPrice()->getBruttoPrice();

            if (!$amount) {
                // Session lost.
                return false;
            }

            $availableInstallmentPlansService = $this->getAvailableInstallmentPlansService();
            $objAvailableInstallmentPlans = $availableInstallmentPlansService->getAvailableInstallmentPlans($amount);
            $availableInstallmentPlans = $objAvailableInstallmentPlans->getAvailableInstallmentPlans();

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

        return false;
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
        $payment = 'Debit';
        $requiredFields = $this->assignRequiredDynValue()[$payment];
        if ($this->_validateRequiredFields($requiredFields,$dynValue) == 1) {
            return 1;
        }
        if (!isset($dynValue['apdebitbankaccount'])
            || !$dynValue['apdebitbankaccount']
        ) {
            return 1; //Complete fields correctly
        }
        $this->_setDynUserValues($dynValue, $requiredFields, $payment);
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
        $payment = 'Installments';
        $requiredFields = $this->assignRequiredDynValue()[$payment];

        if ($this->_validateRequiredFields($requiredFields,$dynValue) == 1) {
            return 1;
        }
        if (!isset($dynValue['apinstallmentbankaccount'])
            || !$dynValue['apinstallmentbankaccount']
        ) {
            return 1; //Complete fields correctly
        }
        $this->_setDynUserValues($dynValue, $requiredFields, $payment);
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

    /**
     * allowAfterpayPayment
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     * @return bool
     */
    public function allowAfterpayPayment() : bool
    {
        $excludedArticlesNrs = Registry::getConfig()->getConfigParam("arvatoAfterpayExcludedArticleNr");

        /** @var ArticleList $basketArticles */
        $basketArticles = $this->getSession()->getBasket()->getBasketArticles();

        foreach ($basketArticles as $article) {
            $article->load($article->oxarticles__oxid->value);
            $articleNum = $article->oxarticles__oxartnum->value;

            if (strpos($excludedArticlesNrs, $articleNum) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * validateInvoice
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     * @param mixed $dynValue
     *
     * @return int
     */
    protected function validateInvoice($dynValue)
    {
        $payment = 'Invoice';
        $requiredFields = $this->assignRequiredDynValue()[$payment];
        $validationResult = $this->_validateRequiredFields($requiredFields,$dynValue);
        if (!$validationResult) {
            $this->_setDynUserValues($dynValue, $requiredFields, $payment);
        }
        return $validationResult;
    }

    /**
     * _validateRequiredFields
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     * @param array  $requiredFields
     * @param string $payment
     * @param array  $dynValue
     *
     * @return int
     */
    protected function _validateRequiredFields($requiredFields, &$dynValue)
    {
        foreach ($this->map as $dynField => $requiredField) {
            if ($requiredFields[$requiredField]
                && (!isset($dynValue[$dynField]) || !$dynValue[$dynField])
            ) {
                return 1; //Complete fields correctly
            }
        }
        return 0;
    }

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
     * getMerchantId
     * ----------------------------------------------------------------------------------------------------------------
     * get Merchant Id from Config (needed for link in tpl)
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
        $privacyLink = str_replace(
            '##MERCHANT##',
            Registry::getConfig()->getConfigParam('arvatoAfterpayHorizonID'.$user->getActiveCountry()),
            $privacyLink
        );

        $smarty->assign('PrivacyLink', $privacyLink);
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

        $AGBLink = str_replace('##LANGCOUNTRY##',$lang.'_'.$country,$links['TC']);
        if ($merchantID = Registry::getConfig()->getConfigParam('arvatoAfterpayHorizonID'.$user->oxuser__oxcountryid->value)) {
            $AGBLink = str_replace('##MERCHANT##', $merchantID, $AGBLink);
        } else {
            $AGBLink = str_replace('##MERCHANT##', 'muster-merchant', $AGBLink);
        }

        $smarty = Registry::getUtilsView()->getSmarty();
        $smarty->assign('AGBLink', $AGBLink);
    }

    /**
     * _setDynUserValues
     * -----------------------------------------------------------------------------------------------------------------
     * check if we have user values in the dynvalues and adds them to the user
     *
     * @param array  $dynValues      dynamic Values from the session or post request
     * @param array  $requiredFields fields required in this case salutation, or other things
     * @param string $payment        payment name
     */
    protected function _setDynUserValues($dynValues, $requiredFields, $payment)
    {
        $userValues = [];
        foreach ($this->map as $dynField => $requiredField) {
            if ($requiredFields[$requiredField] && isset($dynValues[$dynField][$payment]) && isset($this->userMapping[$dynField])) {
                if ($dynField == 'apbirthday') {
                    $userValues[$this->userMapping[$dynField]] = date("d/m/Y", strtotime($dynValues[$dynField][$payment]));
                }
                else {
                    $userValues[$this->userMapping[$dynField]] = $dynValues[$dynField][$payment];
                }
            }
        }

        if (!empty($userValues)) {
            /** @var User $user */
            $user = $this->getUser();
            $user->assign($userValues);
            $user->save();
        }
    }
}
