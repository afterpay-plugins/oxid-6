<?php

/**
 *
*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class AddressEntity: Entitiy for address Data.
 */
class AddressEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Getter for street property.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->getData('street');
    }

    /**
     * Setter for street property.
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->setData('street', $street);
    }

    /**
     * Getter for street number property.
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->getData('streetNumber');
    }

    /**
     * Setter for street number additional property.
     *
     * @param string $streetNumberAdditional
     */
    public function setStreetNumberAdditional($streetNumberAdditional)
    {
        $this->setData('streetNumberAdditional', $streetNumberAdditional);
    }

    /**
     * Getter for street number additional property.
     *
     * @return string
     */
    public function getStreetNumberAdditional()
    {
        return $this->getData('streetNumberAdditional');
    }

    /**
     * Setter for street number property.
     *
     * @param string $streetNumber
     */
    public function setStreetNumber($streetNumber)
    {
        $this->setData('streetNumber', $streetNumber);
    }

    /**
     * Getter for postal code property.
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->getData('postalCode');
    }

    /**
     * Setter for postal code property.
     *
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->setData('postalCode', $postalCode);
    }

    /**
     * Getter for postal place property.
     *
     * @return string
     */
    public function getPostalPlace()
    {
        return $this->getData('postalPlace');
    }

    /**
     * Setter for postal place property.
     *
     * @param string $postalPlace
     */
    public function setPostalPlace($postalPlace)
    {
        $this->setData('postalPlace', $postalPlace);
    }

    /**
     * Getter for country code property.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getData('countryCode');
    }

    /**
     * Setter for country code property.
     *
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->setData('countryCode', $countryCode);
    }

    /**
     * Getter for care of property.
     *
     * @return string
     */
    public function getCareOf()
    {
        return $this->getData('careOf');
    }

    /**
     * Setter for care of property.
     *
     * @param string $careOf
     */
    public function setCareOf($careOf)
    {
        $this->setData('careOf', $careOf);
    }
}
