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

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use OxidEsales\Eshop\Core\Registry;
use Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentEntity;
use Arvato\AfterpayModule\Application\Model\DataProvider\OrderDataProvider;
use Arvato\AfterpayModule\Application\Model\DataProvider\CheckoutCustomerDataProvider;
use Arvato\AfterpayModule\Application\Model\DataProvider\PaymentDataProvider;

/**
 * Class AuthorizePaymentDataProvider: Data provider for autorize payment data.
 */
class AuthorizePaymentDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the data object for an AfterPay authorize payment request.
     *
     * @param oxSession $session
     * @param oxLang $lang
     *
     * @param oxOrder $oOrder
     *
     * @return AuthorizePaymentEntity|object
     */
    public function getDataObject(
        \OxidEsales\Eshop\Core\Session $session,
        \OxidEsales\Eshop\Core\Language $lang,
        \OxidEsales\Eshop\Application\Model\Order $oOrder
    ) {
        // Collect Data
        $user = $session->getUser();
        $basket = $session->getBasket();
        $orderId = $session->getVariable('OrderControllerId');
        $deladrid = $session->getVariable('deladrid');
        $languageAbbr = $lang->getLanguageAbbr();
        $billCustomer = $this->getCustomer($user, $languageAbbr);
        $orderSummary = $this->getOrderSummeryByBasket($basket, $orderId, $oOrder);

        if (isset($deladrid)) {
            $deliveryCustomer = $this->getDelCustomer($user, $languageAbbr);
        }

        $arvatoAfterpayCheckoutId = $session->getVariable('arvatoAfterpayCheckoutId');

        $afterpayContractId = $session->getVariable('arvatoAfterpayContractId');
        $aDynValue = $session->getVariable('dynvalue');

        if ($aDynValue && isset($aDynValue['afterpayInstallmentProfileId'])) {
            $iSelectedInstallmentPlanProfileId = $aDynValue['afterpayInstallmentProfileId'];
        }

        $payment = $this->getPayment($session, $basket, $iSelectedInstallmentPlanProfileId);

        $risk = new \stdClass();
        $risk->channelType = Registry::getConfig()->getConfigParam('arvatoAfterpayRiskChannelType') ?: 'Internet';
        $risk->deliveryType = Registry::getConfig()->getConfigParam('arvatoAfterpayRiskDeliveryType') ?: 'Normal';

        // Assign Data

        $dataObject = oxNew(AuthorizePaymentEntity::class);
        $dataObject->setPayment($payment);
        $dataObject->setCustomer($billCustomer);
        $dataObject->setOrder($orderSummary);
        $dataObject->setRisk($risk);
        isset($deliveryCustomer) && $dataObject->setDeliveryCustomer($deliveryCustomer);
        isset($afterpayContractId) && $dataObject->setContractId($afterpayContractId);
        isset($arvatoAfterpayCheckoutId) && $dataObject->setCheckoutId($arvatoAfterpayCheckoutId);

        return $dataObject;
    }

    ///////////////////////////////////////////////////////////////
    /// @codeCoverageIgnoreStart - Mocking Helper

    /**
     * @param $basket
     * @param $orderId
     *
     * @param oxOrder $oOrder
     *
     * @return OrderEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getOrderSummeryByBasket($basket, $orderId, \OxidEsales\Eshop\Application\Model\Order $oOrder)
    {
        return oxNew(OrderDataProvider::class)->getOrderSummaryByBasket($basket, $orderId, $oOrder);
    }

    /**
     * @param $user
     * @param $languageAbbr
     *
     * @return CheckoutCustomerEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getCustomer($user, $languageAbbr)
    {
        return oxNew(CheckoutCustomerDataProvider::class)->getCustomer($user, $languageAbbr);
    }

    /**
     * @param oxSession $session
     * @param $basket
     * @param $iSelectedInstallmentPlanProfileId
     *
     * @return PaymentEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getPayment(\OxidEsales\Eshop\Core\Session $session, $basket, $iSelectedInstallmentPlanProfileId)
    {
        return oxNew(PaymentDataProvider::class)->getPayment(
            $basket->getPaymentId(),
            $session->getVariable('arvatoAfterpayIBAN'),
            $session->getVariable('arvatoAfterpayBIC'),
            $iSelectedInstallmentPlanProfileId
        );
    }

    /**
     * @param $user
     * @param $languageAbbr
     *
     * @return CheckoutCustomerEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getDelCustomer($user, $languageAbbr)
    {
        return oxNew(CheckoutCustomerDataProvider::class)
            ->getDeliveryCustomer($user, $languageAbbr);
    }
}
