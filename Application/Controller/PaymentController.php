<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Controller;

use Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity;
use Arvato\AfterpayModule\Core\AvailableInstallmentPlansService;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

/**
 * Class PaymentController : Extends payment controller with AfterPay validation call.
 *
 * @extends PaymentController
 *
 */
class PaymentController extends PaymentController_parent
{

    const ARVATO_ORDER_STATE_SELECTINSTALLMENT = -13337;

    protected $map = [];
    /**
     * @var string[] Error messages from the AfterPay service.
     */
    protected $_errorMessages;

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

    public function getTrackingOption()
    {
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
        $alreadyHaveBirthdate = false !== strpos($user->oxuser__oxbirthdate->value, '19');
        $alreadyHavePhone = $user->oxuser__oxfon->value || $user->oxuser__oxmob->value || $user->oxuser__oxprivfon->value;
        $alreadyHaveSal = $user->oxuser__oxsal->value;
        $alreadyHaveZip = $user->oxuser__oxzip->value;
        $alreadyHaveStreet = $user->oxuser__oxstreet->value;
        $alreadyHaveStreetNR = $user->oxuser__oxstreetnr->value;
        $alreadyHaveFName = $user->oxuser__oxfname->value;
        $alreadyHaveLName = $user->oxuser__oxlname->value;
        $alreadyHaveCity = $user->oxuser__oxcity->value;

        foreach (array_keys($requirements) as $payment) {
            $stringHelper = 'arvatoAfterpay' . $payment . 'Requires';

            $requirements[$payment]['Salutation'] =
                (!$alreadyHaveSal && Registry::getConfig()->getConfigParam($stringHelper.'Salutation'. $user->getActiveCountry()));

            $requirements[$payment]['SSN'] =
                Registry::getConfig()->getConfigParam($stringHelper.'SSN'.$user->getActiveCountry());

            $requirements[$payment]['FName'] =
                (!$alreadyHaveFName && Registry::getConfig()->getConfigParam($stringHelper.'FirstName'. $user->getActiveCountry()));

            $requirements[$payment]['LName'] =
                (!$alreadyHaveLName && Registry::getConfig()->getConfigParam($stringHelper.'LastName'. $user->getActiveCountry()));

            $requirements[$payment]['Fon'] =
                (!$alreadyHavePhone && Registry::getConfig()->getConfigParam($stringHelper.'Phone'. $user->getActiveCountry()));

            $requirements[$payment]['Birthdate'] =
                (!$alreadyHaveBirthdate && Registry::getConfig()->getConfigParam($stringHelper.'Birthdate'. $user->getActiveCountry()));

            $requirements[$payment]['Zip'] =
                (!$alreadyHaveZip && Registry::getConfig()->getConfigParam($stringHelper.'Zip'. $user->getActiveCountry()));

            $requirements[$payment]['Street'] =
                (!$alreadyHaveStreet && Registry::getConfig()->getConfigParam($stringHelper.'Street'. $user->getActiveCountry()));

            $requirements[$payment]['StreetNumber'] =
                (!$alreadyHaveStreetNR && Registry::getConfig()->getConfigParam($stringHelper.'StreetNumber'. $user->getActiveCountry()));

            $requirements[$payment]['City'] =
                (!$alreadyHaveCity && Registry::getConfig()->getConfigParam($stringHelper.'City'. $user->getActiveCountry()));
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

        if ($paymentId == "afterpaydebitnote") {
            $error = $this->validateDebitNote($dynValue);
        }

        if ($paymentId == "afterpayinvoice") {
            $error = $this->validateInvoice($dynValue);
        }

        if ($paymentId == "afterpayinstallment") {
            $this->validateAndSaveSelectedInstallmentPforileId($dynValue);
            $error = $this->validateInstallment($dynValue);
        }

        if ($this->getRequestOrSessionParameter('AfterPayTrackingEnabled')) {
            Registry::getSession()->setVariable('AfterPayTrackingEnabled', true);
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
        $payment = 'Debit';
        $requiredFields = $this->assignRequiredDynValue()[$payment];
        if ($this->_validateRequiredFields($requiredFields,$payment,$dynValue) == 1) {
            return 1;
        }
        if (
            !isset($dynValue['apdebitbankaccount'])
            || !$dynValue['apdebitbankaccount']
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
        $payment = 'Installments';
        $requiredFields = $this->assignRequiredDynValue()[$payment];

        if ($this->_validateRequiredFields($requiredFields,$payment,$dynValue) == 1) {
            return 1;
        }
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

        return $this->_validateRequiredFields($requiredFields,$payment,$dynValue);
    }

    /**
     * _validateRequiredFields
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     * @param $requiredFields
     * @param $payment
     * @param $dynValue
     *
     * @return int
     */
    protected function _validateRequiredFields($requiredFields,$payment ,$dynValue)
    {
        foreach ($this->map as $dynField => $requiredField) {
            if ($requiredFields[$requiredField] &&
                (!isset($dynValue[$dynField][$payment]) || !$dynValue[$dynField][$payment])
            ) {
                return 1; //Complete fields correctly
            }
        }
        return 0;
    }
}
