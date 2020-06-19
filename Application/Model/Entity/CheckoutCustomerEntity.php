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
 * Class arvatoAfterpayCustomerEntity: Entity for customer Data.
 */
class CheckoutCustomerEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Constants for customer category property.
     */
    const CUSTOMER_CATEGORY_COMPANY = 'Company';
    const CUSTOMER_CATEGORY_PERSON = 'Person';

    /**
     * Constants for salutation property.
     */
    const SALUTATION_MR = 'Mr';
    const SALUTATION_MRS = 'Mrs';
    const SALUTATION_MISS = 'Miss';

    /**
     * Getter for salutation property.
     *
     * @return string
     */
    public function getSalutation()
    {
        return $this->getData('salutation');
    }

    /**
     * Setter for salutation property.
     *
     * @param string $salutation
     */
    public function setSalutation($salutation)
    {
        $this->setData('salutation', $salutation);
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
     * Getter for email property.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData('email');
    }

    /**
     * Setter for email property.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->setData('email', $email);
    }

    /**
     * Getter for customer category property.
     *
     * @return string
     */
    public function getCustomerCategory()
    {
        return $this->getData('customerCategory');
    }

    /**
     * Setter for customer category property.
     *
     * @param string $customerCategory
     */
    public function setCustomerCategory($customerCategory)
    {
        $this->setData('customerCategory', $customerCategory);
    }

    /**
     * Getter for address property.
     *
     * @return AddressEntity
     */
    public function getAddress()
    {
        return $this->getData('address');
    }

    /**
     * Setter for address property.
     *
     * @param AddressEntity $address
     */
    public function setAddress(AddressEntity $address)
    {
        $this->setData('address', $address);
    }

    /**
     * Getter for conversation language property.
     *
     * @return string
     */
    public function getConversationLanguage()
    {
        return $this->getData('conversationLanguage');
    }

    /**
     * Setter for conversation language property.
     *
     * @param string $conversationLanguage
     */
    public function setConversationLanguage($conversationLanguage)
    {
        $this->setData('conversationLanguage', $conversationLanguage);
    }

    /**
     * Getter for identification number property.
     *
     * @return string
     */
    public function getIdentificationNumber()
    {
        return $this->getData('identificationNumber');
    }

    /**
     * Setter for identification number property.
     *
     * @param string $identificationNumber
     */
    public function setIdentificationNumber($identificationNumber)
    {
        $this->setData('identificationNumber', $identificationNumber);
    }

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
     * Getter for phone property.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getData('phone');
    }

    /**
     * Setter for phone property.
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->setData('phone', $phone);
    }

    /**
     * Getter for mobile phone property.
     *
     * @return string
     */
    public function getMobilePhone()
    {
        return $this->getData('mobilePhone');
    }

    /**
     * Setter for mobile phone property.
     *
     * @param string $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->setData('mobilePhone', $mobilePhone);
    }

    /**
     * Getter for birth date property.
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->getData('birthDate');
    }

    /**
     * Setter for birth date property.
     *
     * @param string $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->setData('birthDate', $birthDate);
    }
}
