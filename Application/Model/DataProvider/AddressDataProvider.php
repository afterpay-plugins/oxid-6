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

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

/**
 * Class AddressDataProvider: Data provider for address data.
 */
class AddressDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the address object of a given user.
     *
     * @param \OxidEsales\Eshop\Application\Model\User $user
     * @return AddressEntity
     */
    public function getUserAddress(\OxidEsales\Eshop\Application\Model\User $user)
    {
        $dataObject = oxNew(\AfterpayModule\Application\Model\Entity\AddressEntity::class);
        $dataObject->setCountryCode($this->getCountryCode($user->oxuser__oxcountryid->value));
        $dataObject->setPostalCode($user->oxuser__oxzip->value);
        $dataObject->setStreet($user->oxuser__oxstreet->value);
        $dataObject->setStreetNumber($user->oxuser__oxstreetnr->value);
        $dataObject->setStreetNumberAdditional($user->oxuser__oxaddinfo->value);
        $dataObject->setPostalPlace($user->oxuser__oxcity->value);
        $dataObject->setCareOf($user->oxuser__oxcompany->value);

        return $dataObject;
    }

    /**
     * Gets the delivery address object for a given user.
     *
     * @param \OxidEsales\Eshop\Application\Model\User $user
     * @return AddressEntity
     */
    public function getDeliveryAddress(\OxidEsales\Eshop\Application\Model\User $user)
    {
        /** @var oxAddress $address */
        $address = $user->getSelectedAddress();

        if (!empty($address)) {
            $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AddressEntity::class);
            $dataObject->setCountryCode($this->getCountryCode($address->oxaddress__oxcountryid->value));
            $dataObject->setPostalCode($address->oxaddress__oxzip->value);
            $dataObject->setStreet($address->oxaddress__oxstreet->value);
            $dataObject->setStreetNumber($address->oxaddress__oxstreetnr->value);
            $dataObject->setStreetNumberAdditional($address->oxaddress__oxaddinfo->value);
            $dataObject->setPostalPlace($address->oxaddress__oxcity->value);
            $dataObject->setCareOf($address->oxaddress__oxcompany->value);

            return $dataObject;
        } else {
            return $this->getUserAddress($user);
        }
    }

    /**
     * Gets the two digit ISO code for a country id.
     *
     * @param string $id
     * @return string
     */
    protected function getCountryCode($id)
    {
        $country = oxNew(\OxidEsales\Eshop\Application\Model\Country::class);
        $country->load($id);

        return $country->oxcountry__oxisoalpha2->value;
    }
}
