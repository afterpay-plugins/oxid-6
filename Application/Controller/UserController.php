<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;

/**
 * Class UserController : Extends user controller with AfterPay customer facing message getter
 * @codeCoverageIgnore Only getter, no logic
 */
class UserController extends UserController_parent
{

    /**
     * render
     * -----------------------------------------------------------------------------------------------------------------
     * Extension: Add Error Message to display
     *
     * @return mixed
     */
    public function render()
    {
        $return = parent::render();
        $errorMessage = $this->getCustomerFacingMessage();
        if(isset($errorMessage) && !empty($errorMessage)) {
            oxNew(UtilsView::class)->addErrorToDisplay($this->getCustomerFacingMessage());
        }
        return $return;
    }

    /**
     * @return string CustomerFacingMessage
     */
    public function getCustomerFacingMessage()
    {
        $session = Registry::getSession();
        return $session->getVariable('arvatoAfterpayCustomerFacingMessage');
    }
}
