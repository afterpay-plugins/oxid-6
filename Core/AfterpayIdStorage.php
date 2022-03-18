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

namespace Arvato\AfterpayModule\Core;

class AfterpayIdStorage
{
    /**
     * getContries
     * -----------------------------------------------------------------------------------------------------------------
     * Get Afterpay counties
     * Source: https://developer.afterpay.io/documentation/prepare-checkout/collecting-consumer-information/
     *
     * @return string[]
     */
    public function getContries()
    {
        return [
            'Germany',
            'Austria',
            'Switzerland',
            'Netherlands',
            'Belgium',
            'Sweden',
            'Norway',
            'Finland',
            'Denmark',
        ];
    }

    /**
     * getFields
     * -----------------------------------------------------------------------------------------------------------------
     * get the fields for admin UI afterpay configs
     *
     * @return string[]
     */
    public function getFields()
    {
        return [
            'Salutation',
            'SSN',
            'Phone',
            'Birthdate',
            'StreetNumber',
            'Privacy',
            'TC',
        ];
    }
}
