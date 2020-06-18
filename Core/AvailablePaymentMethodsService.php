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

namespace Arvato\AfterpayModule\Core;

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
     * @param oxSession $session
     * @param oxLang $lang
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
     * @param $iProfileId
     * @param bool $bRequireDirectDebit
     *
     * @return bool
     */
    public function isSpecificInstallmentAvailable($iProfileId, $bRequireDirectDebit = true)
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
                (!$bRequireDirectDebit || $stdClassMethod->directDebit->available)
            ) {
                $this->_mappingInstallmentPfofileId2NumberOfInstallments[$stdClassMethod->installment->installmentProfileNumber]
                    = $stdClassMethod->installment->numberOfInstallments;

                if ($iProfileId == $stdClassMethod->installment->installmentProfileNumber) {
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
     * @param $iProfileId
     *
     * @return null|int
     */
    public function getNumberOfInstallmentsByProfileId($iProfileId)
    {

        if (!$this->_mappingInstallmentPfofileId2NumberOfInstallments) {
            $this->isSpecificInstallmentAvailable(1);
        }
        if (isset($this->_mappingInstallmentPfofileId2NumberOfInstallments[$iProfileId])) {
            return $this->_mappingInstallmentPfofileId2NumberOfInstallments[$iProfileId];
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

        $aErrors = $this->_entity->getErrors();

        if (is_array($aErrors)) {
            $aErrors = reset($aErrors);
            if (is_array($aErrors)) {
                $oError = reset($aErrors);
                if (isset($oError->actionCode)) {
                    if ('AskConsumerToReEnterData' == $oError->actionCode || 'AskConsumerToConfirm' == $oError->actionCode) {
                        $this->_iLastErrorNo = oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class)->getOrderStateCheckAddressConstant();
                    }
                }
            }
        }

        return parent::getLastErrorNo();
    }
}
