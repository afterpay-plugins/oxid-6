<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity;
use Arvato\AfterpayModule\Application\Model\Entity\OrderEntity;
use Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;
use Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentEntity;
use OxidEsales\Eshop\Core\Session;

/**
 * Class AuthorizePaymentDataProvider: Data provider for autorize payment data.
 */
class AuthorizePaymentDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the data object for an AfterPay authorize payment request.
     *
     * @param Session $session
     * @param Language $lang
     *
     * @param Order $order
     *
     * @return AuthorizePaymentEntity|object
     */
    public function getDataObject(
        \OxidEsales\Eshop\Core\Session $session,
        \OxidEsales\Eshop\Core\Language $lang,
        \OxidEsales\Eshop\Application\Model\Order $order
    ) {
        // Collect Data
        $user = $session->getUser();
        $basket = $session->getBasket();
        $orderId = $session->getVariable('OrderControllerId');
        $deladrid = $session->getVariable('deladrid');
        $languageAbbr = $lang->getLanguageAbbr();
        $billCustomer = $this->getCustomer($user, $languageAbbr, (bool) $session->getVariable('AfterPayTrackingEnabled'));
        $orderSummary = $this->getOrderSummeryByBasket($basket, $orderId, $order);

        if (isset($deladrid)) {
            $deliveryCustomer = $this->getDelCustomer($user, $languageAbbr);
        }

        $arvatoAfterpayCheckoutId = $session->getVariable('arvatoAfterpayCheckoutId');

        $afterpayContractId = $session->getVariable('arvatoAfterpayContractId');
        $dynValue = $session->getVariable('dynvalue');

        if ($dynValue && isset($dynValue['afterpayInstallmentProfileId'])) {
            $selectedInstallmentPlanProfileId = $dynValue['afterpayInstallmentProfileId'];
        }

        $payment = $this->getPayment($session, $basket, $selectedInstallmentPlanProfileId);

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
     * @param Order $order
     *
     * @return OrderEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getOrderSummeryByBasket($basket, $orderId, \OxidEsales\Eshop\Application\Model\Order $order)
    {
        return oxNew(OrderDataProvider::class)->getOrderSummaryByBasket($basket, $orderId, $order);
    }

    /**
     * @param $user
     * @param $languageAbbr
     * @param $trackingEnabled
     *
     * @return CheckoutCustomerEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getCustomer($user, $languageAbbr, $trackingEnabled)
    {
        return oxNew(CheckoutCustomerDataProvider::class)->getCustomer($user, $languageAbbr, $trackingEnabled);
    }

    /**
     * @param Session $session
     * @param $basket
     * @param $selectedInstallmentPlanProfileId
     *
     * @return PaymentEntity
     * @codeCoverageIngore Mocking helper
     */
    protected function getPayment(\OxidEsales\Eshop\Core\Session $session, $basket, $selectedInstallmentPlanProfileId)
    {
        return oxNew(PaymentDataProvider::class)->getPayment(
            $basket->getPaymentId(),
            $session->getVariable('arvatoAfterpayIBAN'),
            $session->getVariable('arvatoAfterpayBIC'),
            $selectedInstallmentPlanProfileId
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
