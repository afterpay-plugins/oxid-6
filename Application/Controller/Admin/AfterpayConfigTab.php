<?php
/**
 * ${CARET}
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @package
 * @copyright       ©2022 norisk GmbH
 *
 * @author          Anas Sadek <asadek@noriskshop.de>
 */

namespace Arvato\AfterpayModule\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;

class AfterpayConfigTab extends ShopConfiguration
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'afterpay_config_tab.tpl';

    /**
     * render
     * -----------------------------------------------------------------------------------------------------------------
     * render the nr404 config page
     *
     * @compatibleOxidVersion 6.0
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        return $this->_sThisTemplate;
    }
    /**
     * return theme filter for config variables
     *
     * @return string
     */
    protected function _getModuleForConfigVars()
    {
        return 'module:arvatoafterpay';
    }
}
