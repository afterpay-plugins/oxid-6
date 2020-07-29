<?php

/** @noinspection PhpLongTypeFormInspection */

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class CustomerResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Getter for customer number property.
     *
     * @return string
     */
    public function getCustomerNumber()
    {
        return $this->getData('customerNumber');
    }

    /**
     * Setter for customer number property.
     *
     * @param string $customerNumber
     */
    public function setCustomerNumber($customerNumber)
    {
        $this->setData('customerNumber', $customerNumber);
    }

    /**
     * Getter for first name property.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getData('firstName');
    }

    /**
     * Setter for first name property.
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->setData('firstName', $firstName);
    }

    /**
     * Getter for last name property.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getData('lastName');
    }

    /**
     * Setter for last name property.
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->setData('lastName', $lastName);
    }

    /**
     * Getter for address list property.
     *
     * @return AddressEntity[]
     */
    public function getAddressList()
    {
        return $this->getData('addressList');
    }

    /**
     * Setter for address list property.
     *
     * @param AddressEntity[] $addressList
     */
    public function setAddressList($addressList)
    {
        $this->setData('addressList', $addressList);
    }

    /**
     * Adds an address.
     *
     * @param AddressEntity $address
     */
    public function addAddress(AddressEntity $address)
    {
        $this->addItem('addressList', $address);
    }

    /**
     * Removes an address.
     *
     * @param int $index
     */
    public function removeAddress($index)
    {
        $this->addItem('addressList', $index);
    }
}
