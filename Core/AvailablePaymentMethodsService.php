<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Session;
use stdClass;

/**
 * Class AvailablePaymentMethodsService
 */
class AvailablePaymentMethodsService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * @var array mapping of installmentPfofileId to numberOfInstallments
     */
    protected $_mappingInstallmentPfofileId2NumberOfInstallments = [];
    protected $arvatoInstallmentActive;

    /**
     * Standard constructor.
     *
     * @param Session $session
     * @param Language $lang
     */
    public function __construct(\OxidEsales\Eshop\Core\Session $session, \OxidEsales\Eshop\Core\Language $lang, \OxidEsales\Eshop\Application\Model\Order $order)
    {
        $this->_session = $session;
        $this->_lang = $lang;
        $this->_order = $order;
    }

    /**
     * gets available payment methods
     *
     * @return string Outcome
     */
    public function getAvailablePaymentMethods()
    {
        if (isset($this->_entity)) {
            return $this->_entity->getPaymentMethods();
        }

        $response = $this->executeRequestFromSessionData();

        $this->_entity = $this->parseResponse($response);

        if (isset($this->_entity)) {
            $this->_session->setVariable('arvatoAfterpayCheckoutId', $this->_entity->getCheckoutId());
            return $this->_entity->getPaymentMethods();
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isInvoiceAvailable()
    {

        $this->getAvailablePaymentMethods();

        if (is_array($this->_entity->getPaymentMethods()) and count($this->_entity->getPaymentMethods())) {
            foreach ($this->_entity->getPaymentMethods() as $stdClassMethod) {
                if ($stdClassMethod->type == 'Invoice' && !isset($stdClassMethod->directDebit)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $profileId
     * @param bool $requireDirectDebit
     *
     * @return bool
     */
    public function isSpecificInstallmentAvailable($profileId, $requireDirectDebit = true)
    {

        $paymentMethods = $this->getAvailablePaymentMethods();

        if (!is_array($paymentMethods) || !count($paymentMethods)) {
            return false;
        }

        foreach ($paymentMethods as $stdClassMethod) {
            if (
                $stdClassMethod->type == 'Installment' &&
                isset($stdClassMethod->installment) &&
                isset($stdClassMethod->installment->installmentProfileNumber) &&
                (!$requireDirectDebit || $stdClassMethod->directDebit->available)
            ) {
                $this->_mappingInstallmentPfofileId2NumberOfInstallments[$stdClassMethod->installment->installmentProfileNumber]
                    = $stdClassMethod->installment->numberOfInstallments;

                if ($profileId == $stdClassMethod->installment->installmentProfileNumber) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDirectDebitAvailable()
    {

        if (!isset($this->_entity)) {
            $this->getAvailablePaymentMethods();
        }

        if (is_array($this->_entity->getPaymentMethods()) and count($this->_entity->getPaymentMethods())) {
            foreach ($this->_entity->getPaymentMethods() as $stdClassMethod) {
                if ($stdClassMethod->type == 'Invoice' && isset($stdClassMethod->directDebit) && $stdClassMethod->directDebit) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @codeCoverageIgnore: We really can't test this without mocking both lines.
     * @return stdClass|stdClass[]
     */
    protected function executeRequestFromSessionData()
    {
        $data = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AvailablePaymentMethodsDataProvider::class)->getDataObject(
            $this->_session,
            $this->_lang,
            $this->_order
        )->exportData();
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getAvailablePaymentMethodsClient()->execute($data);
    }

    /**
     * @codeCoverageIgnore Works directly on API Server response. Impossible to test without major server mocking.
     */
    public function getLastErrorNo()
    {
        if (parent::getLastErrorNo() || !isset($this->_entity)) {
            return parent::getLastErrorNo();
        }

        $errors = $this->_entity->getErrors();

        if (is_array($errors)) {
            $errors = reset($errors);
            if (is_array($errors)) {
                $error = reset($errors);
                if (isset($error->actionCode)) {
                    if ('AskConsumerToReEnterData' == $error->actionCode || 'AskConsumerToConfirm' == $error->actionCode) {
                        $this->_iLastErrorNo = oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class)->getOrderStateCheckAddressConstant();
                    }
                }
            }
        }

        return parent::getLastErrorNo();
    }

    /**
     * installmentPaymentActive
     * -----------------------------------------------------------------------------------------------------------------
     *
     */
    public function installmentPaymentActive(): bool
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

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return AvailableInstallmentPlansService
     */
    protected function getAvailableInstallmentPlansService(): AvailableInstallmentPlansService
    {
        return oxNew(\Arvato\AfterpayModule\Core\AvailableInstallmentPlansService::class);
    }

    /**
     * @return bool|array [AvailableInstallmentPlansResponseEntity]
     */
    public function getAvailableInstallmentPlans(): bool|array
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

            return false;
        }

        return false;
    }
}
