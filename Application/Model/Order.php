<?php

namespace Arvato\AfterpayModule\Application\Model;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\OrderArticle;
use OxidEsales\Eshop\Application\Model\PaymentGateway as PaymentGatewayOxid;
use OxidEsales\Eshop\Application\Model\UserPayment;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class Order
 * @extends \OxidEsales\Eshop\Application\Model\Order
 *
 *  Naming of the "*Order"-Classes:
 *    - Order:           Extension of order - model <-- THIS FILE
 *    - OrderController: Extension of order - view
 *    - AfterpayOrder:   New model as seen in db table afterpayorder
 */
class Order extends Order_parent
{

    /**
     * Make _setNumber public so it can be triggered out of payment gateway,
     * right before executePayment demands the order nr.
     *
     * @return bool Number generation success
     */
    public function setNumber()
    {
        $return = $this->_setNumber();
        $session = Registry::getSession();
        $session->setVariable('OrderControllerId', $this->oxorder__oxordernr->value);
        return $return;
    }

    /**
     * Returns the correct gateway. At the moment only switch between default
     * and IPayment, can be extended later.
     *
     * @return PaymentGateway|PaymentGatewayOxid|object Payment gateway object
     *
     * @phpcs:disable
     */
    protected function _getGateway()
    {
        if (!$this->isAfterpayPaymentType()) {
            return parent::_getGateway();
        }
        return oxNew(PaymentGateway::class);
    }

    /**
     * Checks if the selected payment method for this order is an Afterpay payment method
     *
     * @param string $paymenttype payment id. Defaults to current orderobject payment id
     *
     * @return bool
     */
    public function isAfterpayPaymentType($paymenttype = null)
    {
        if (!$paymenttype) {
            $paymenttype = $this->oxorder__oxpaymenttype->value;
        }
        return (0 === strpos($paymenttype, 'afterpay'));
    }

    /**
     * Checks type of selected payment method
     *
     * @param string $paymenttype payment id. Defaults to current orderobject payment id
     *
     * @return bool
     */
    public function isAfterpayDebitNote($paymenttype = null)
    {
        if (!$paymenttype) {
            $paymenttype = $this->oxorder__oxpaymenttype->value;
        }
        return ('afterpaydebitnote' === $paymenttype);
    }

    /**
     * Checks type of selected payment method
     *
     * @param string $paymenttype payment id. Defaults to current orderobject payment id
     *
     * @return bool
     */
    public function isAfterpayInvoice($paymenttype = null)
    {
        if (!$paymenttype) {
            $paymenttype = $this->oxorder__oxpaymenttype->value;
        }
        return ('afterpayinvoice' === $paymenttype);
    }

    /**
     * Checks type of selected payment method
     *
     * @param string $paymenttype payment id. Defaults to current orderobject payment id
     *
     * @return bool
     */
    public function isAfterpayInstallment($paymenttype = null)
    {
        if (!$paymenttype) {
            $paymenttype = $this->oxorder__oxpaymenttype->value;
        }
        return ('afterpayinstallment' === $paymenttype);
    }

    /**
     * Returns the AfterpayOrder of this order,
     * Test with isLoaded() for success.
     *
     * @return AfterpayOrder
     */
    public function getAfterpayOrder()
    {
        $aporder = oxNew(AfterpayOrder::class, $this);

        if (!$this->isAfterpayPaymentType()) {
            return $aporder;
        }

        $aporder->load($this->getId());

        if (!$aporder->isLoaded()) {
            //For creation during checkout:
            $aporder->setId($this->getId());
        }

        return $aporder;
    }

    /**
     * _executePayment
     * -----------------------------------------------------------------------------------------------------------------
     *
     * @param Basket      $basket
     * @param UserPayment $userPayment
     * @return bool
     */
    protected function _executePayment(Basket $basket, $userPayment)
    {
        if ($this->isAfterpayPaymentType()) {
            return $this->_executeAfterpayPayment($basket, $userPayment);
        }

        return parent::_executePayment($basket, $userPayment);
    }

    /**
     * _executeAfterpayPayment
     * -----------------------------------------------------------------------------------------------------------------
     * APM-23: Extended to NOT delete the order should executePayment fail
     *
     * @param Basket $basket
     * @param        $userPayment
     * @return bool|int|string|null
     */
    protected function _executeAfterpayPayment(Basket $basket, $userPayment)
    {
        $gateway = $this->_getGateway();
        $gateway->setPaymentParams($userPayment);

        if (!$gateway->executePayment($basket->getPrice()->getBruttoPrice(), $this)) {
            // Explicitly NOT deleting the order in here
            // Deleting the session variable to ensure that the users next order try is treated as a new order
            // Without deleting it the user can just try ordering again after failure and gets a success mail+thankyou
            Registry::getSession()->deleteVariable('sess_challenge');
            // Article amount has already been reduced in OrderArticle::save. Needs to be reset now
            /** @var OrderArticle $orderArticle */
            foreach ($this->getOrderArticles() as $orderArticle) {
                $allowNegativeStock = Registry::getConfig()->getConfigParam('blAllowNegativeStock');
                $amount = $orderArticle->getFieldData('oxamount');
                $orderArticle->updateArticleStock($amount, $allowNegativeStock);
            }

            // checking for error messages
            if (method_exists($gateway, 'getLastError') && ($lastError = $gateway->getLastError())) {
                return $lastError;
            }

            // checking for error codes
            if (method_exists($gateway, 'getLastErrorNo') && ($lastErrorNo = $gateway->getLastErrorNo())) {
                return $lastErrorNo;
            }

            return self::ORDER_STATE_PAYMENTERROR; // means no authentication
        }

        return true; // everything fine
    }
}
