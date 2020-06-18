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

namespace Arvato\AfterpayModule\Application\Controller;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class UserController : Extends user controller with AfterPay customer facing message getter
 * @codeCoverageIgnore Only getter, no logic
 */
class UserController extends UserController_parent
{
    /**
     * @return string CustomerFacingMessage
     */
    public function getCustomerFacingMessage()
    {
        $oxSession = Registry::getSession();
        return $oxSession->getVariable('arvatoAfterpayCustomerFacingMessage');
    }
}
