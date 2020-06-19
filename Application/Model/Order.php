<?php

/**
 *
*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */

namespace Arvato\AfterpayModule\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class Order
 * @extends \oxorder
 *
 *  Naming of the "*Order"-Classes:
 *    - ArvatoAfterpayOxOrder: Exctension of oxOrder - model
 *    - OrderController: Exctension of order - view
 *    - AfterpayOrder: New model as seen in db table afterpayorder
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
     * Try to tick oxcounter back one number to avoid holes in the consecutive order numbering.
     * That reset will fail intentionally without any error if there has been another order meanwhile.
     * That means that holes still can occure, but they should occur only in rare conditions.
     */
    public function resetNumber()
    {
        $session = Registry::getSession();
        $session->setVariable('OrderControllerId', 'resetIn' . __FILE__ . __LINE__);

        $ident = $this->_getCounterIdent();

        $db = DatabaseProvider::getDb();
        $db->startTransaction();

        $q = "SELECT `oxcount` FROM `oxcounters` WHERE `oxident` = " . $db->quote($ident) . " FOR UPDATE";

        $cnt = $db->getOne($q, false);

        if (!(int)$cnt || (int)$this->oxorder__oxordernr->value !== (int)$cnt) {
            // Meanwhile there was another order
            return;
        }

        $cnt = ((int)$cnt) - 1;
        $q = "UPDATE `oxcounters` SET `oxcount` = ? WHERE `oxident` = ?";
        $db->execute($q, [$cnt, $ident]);

        $db->commitTransaction();
    }

    /**
     * Returns the correct gateway. At the moment only switch between default
     * and IPayment, can be extended later.
     *
     * @return object $oPayTransaction payment gateway object
     *
     * @phpcs:disable
     */
    protected function _getGateway()
    {
        if (!$this->isAfterpayPaymentType()) {
            return parent::_getGateway();
        }
        return oxNew(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class);
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
        $aporder = oxNew(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::class, $this);

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
}
