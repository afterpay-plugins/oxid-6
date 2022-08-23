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
use OxidEsales\Eshop\Core\Registry;
use Arvato\AfterpayModule\Core\AfterpayIdStorage;

class AfterpayRequiredfieldsTab extends ShopConfiguration
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'afterpay_requiredfields_tab.tpl';

    /**
     * _getModuleForConfigVars
     * -----------------------------------------------------------------------------------------------------------------
     * return theme filter for config variables
     *
     * @return string
     */
    protected function _getModuleForConfigVars()
    {
        return 'module:arvatoafterpay';
    }

    /**
     * getCountries
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     */
    public function getCountries()
    {
        return Registry::get(AfterpayIdStorage::class)->getContries();
    }

    /**
     * getFields
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     */
    public function getFields()
    {
        return Registry::get(AfterpayIdStorage::class)->getFields();
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
        return "SHOP_MODULE_GROUP_arvatoAfterpayRequiredFields";
    }

    /**
     * isFixRequiredField
     * -----------------------------------------------------------------------------------------------------------------
     *  check if the field fix required
     *
     * @param $FieldNames
     * @param $country_name
     *
     * @return bool
     */
    public function isFixRequiredField($FieldNames, $country_name)
    {
        $fixRequiredFields = Registry::get(AfterpayIdStorage::class)->getFixRequiredFields();

        if ($fixRequiredFields[$FieldNames][$country_name] == 1 ) {
            return true;
        }
        return false;
    }
}
