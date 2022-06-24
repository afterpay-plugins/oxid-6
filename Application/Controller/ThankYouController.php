<?php
/**
 * ThankYou Controller
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @package
 * @copyright       ©2022 norisk GmbH
 *
 * @author          Anas Sadek <asadek@noriskshop.de>
 */

namespace Arvato\AfterpayModule\Application\Controller;

class ThankYouController extends ThankYouController_parent
{
    /**
     * checkIfAfterPayPayment
     * -----------------------------------------------------------------------------------------------------------------
     *  return true if the Payment with AfterPay was
     *
     * @return bool
     */
    public function checkIfAfterPayPayment(): bool
    {
        $paymentId = $this->getBasket()->getPaymentId();
        if (substr($paymentId, 0,strlen("afterpay")) == "afterpay") {
            return true;
        }
        return false;
    }
}