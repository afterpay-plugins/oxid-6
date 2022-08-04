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
            'a7c40f631fc920687.20179984' => 'Germany',
            'a7c40f6320aeb2ec2.72885259' => 'Austria',
            'a7c40f6321c6f6109.43859248' => 'Switzerland',
            'a7c40f632cdd63c52.64272623' => 'Netherlands',
            'a7c40f632e04633c9.47194042' => 'Belgium',
            'a7c40f632848c5217.53322339' => 'Sweden',
            '8f241f11096176795.61257067' => 'Norway',
            'a7c40f63293c19d65.37472814' => 'Finland',
            '8f241f110957e6ef8.56458418' => 'Denmark',
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
            'TC_Privacy',
        ];
    }

    /**
     * getTCPrivacyLinks
     * -----------------------------------------------------------------------------------------------------------------
     * get T&C and Privacy Links for APM to generate it for Checkout frontend
     *
     * @return string[]
     */
    public function getTCPrivacyLinks()
    {
        return [
            'TC' => 'https://documents.myafterpay.com/consumer-terms-conditions/##LANGCOUNTRY##/##MERCHANT##/##PAYMENT##',
            'privacy' => 'https://documents.myafterpay.com/privacy-statement/##LANGCOUNTRY##/##MERCHANT##',
        ];
    }

    /**
     * getFixRequiredFields
     * -----------------------------------------------------------------------------------------------------------------
     * get fix required fields pro Country depending on this Table:
     * https://developer.afterpay.io/documentation/prepare-checkout/collecting-consumer-information/
     *
     * @return array
     */
    public function getFixRequiredFields()
    {
        $countries = $this->getContries();
        $fixCountriesFields = [];
        foreach ($countries as $country) {
            switch ($country) {
                case "Switzerland":
                case "Austria":
                case "Germany":
                    $fixCountriesFields["Birthdate"][$country] = 1;
                    $fixCountriesFields["StreetNumber"][$country] = 1;
                    break;
                case "Belgium":
                case "Netherlands":
                    $fixCountriesFields["Birthdate"][$country] = 1;
                    $fixCountriesFields["StreetNumber"][$country] = 1;
                    $fixCountriesFields["Phone"][$country] = 1;
                    break;
                case "Norway":
                case "Finland":
                case "Sweden":
                    $fixCountriesFields["SSN"][$country] = 1;
                    break;
                case "Denmark":
                    $fixCountriesFields["SSN"][$country] = 1;
                    $fixCountriesFields["Birthdate"][$country] = 1;
                    $fixCountriesFields["StreetNumber"][$country] = 1;
                    break;
            }
        }
        return $fixCountriesFields;
    }
}
