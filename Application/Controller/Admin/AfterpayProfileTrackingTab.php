<?php
/**
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @package
 * @copyright       ©2022 norisk GmbH
 *
 * @author          Anas Sadek <asadek@noriskshop.de>
 */

namespace Arvato\AfterpayModule\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;

class AfterpayProfileTrackingTab extends ShopConfiguration
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'afterpay_profiletracking_tab.tpl';

    /**
     * return theme filter for config variables
     *
     * @return string
     */
    protected function _getModuleForConfigVars()
    {
        return 'module:arvatoafterpay';
    }

    /**
     * getViewId
     * -----------------------------------------------------------------------------------------------------------------
     * Role management for Admin UI
     *
     * @compatibleOxidVersion 5.2.x
     *
     * @return string
     */
    public function getViewId()
    {
        return "SHOP_MODULE_GROUP_arvatoAfterpayProfileTracking";
    }
}
