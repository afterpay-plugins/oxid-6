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
 * @author    ©2020 norisk GmbH
 * @link
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace Arvato\AfterpayModule\Core;

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

    /**
     * Standard constructor.
     *
     * @param Session $session
     * @param Language $lang
     */
    public function __construct(\OxidEsales\Eshop\Core\Session $session, \OxidEsales\Eshop\Core\Language $lang, \OxidEsales\Eshop\Application\Model\Order $oOrder)
    {
        $this->_session = $session;
        $this->_lang = $lang;
        $this->_oxOrder = $oOrder;
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

        $aPaymentMethods = $this->getAvailablePaymentMethods();

        if (!is_array($aPaymentMethods) || !count($aPaymentMethods)) {
            return false;
        }

        foreach ($aPaymentMethods as $stdClassMethod) {
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
            $this->_oxOrder
        )->exportData();
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getAvailablePaymentMethodsClient()->execute($data);
    }

    /**
     * Returns the number of installments (used for createContract) by profileId
     *
     * @param $profileId
     *
     * @return null|int
     */
    public function getNumberOfInstallmentsByProfileId($profileId)
    {

        if (!$this->_mappingInstallmentPfofileId2NumberOfInstallments) {
            $this->isSpecificInstallmentAvailable(1);
        }
        if (isset($this->_mappingInstallmentPfofileId2NumberOfInstallments[$profileId])) {
            return $this->_mappingInstallmentPfofileId2NumberOfInstallments[$profileId];
        }
        return null;
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
}
