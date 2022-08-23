<?php

namespace Arvato\AfterpayModule\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    /**
     * Returns afterpay logo URL based on setting
     *
     * @see https://developer.afterpay.io/documentation/logo-and-visual-guidelines/ for logos
     * @return string
     */
    public function getAfterpayLogoUrl()
    {
        $logo = Registry::getConfig()->getConfigParam('arvatoAfterpayLogo', 'Checkout');
        if ($logo) {
            $logo = '_' . strtolower($logo);
        }

        return "https://cdn.myafterpay.com/logo/AfterPay_logo{$logo}.svg";
    }
}
