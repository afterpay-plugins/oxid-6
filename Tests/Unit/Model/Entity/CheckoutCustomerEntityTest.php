<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category      module
 * @package       afterpay
 * @author        ©2020 norisk GmbH
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\AddressEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity;

/**
 * Class CheckoutCustomerEntityTest: unit tests for CheckoutCustomerEntity.
 */
class CheckoutCustomerEntityTest extends EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $address = oxNew(AddressEntity::class);
        $address->setPostalPlace('Paris');

        $testData = [
            'customerCategory'     => CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON,
            'identificationNumber' => '12345abcde',
            'address'              => $address,
            'firstName'            => 'Paulchen',
            'lastName'             => 'Panther',
            'customerNumber'       => 'abced12345',
            'salutation'           => CheckoutCustomerEntity::SALUTATION_MR,
            'email'                => 'panther@blakeedwards.com',
            'phone'                => '+49/0800/12345678',
            'mobilePhone'          => '+49/0171/12345678',
            'birthDate'            => '1963-12-19',
            'conversationLanguage' => 'FR',
        ];

        $testObject = oxNew(CheckoutCustomerEntity::class);
        $this->getSet($testObject, $testData);

        $this->assertEquals((object) [
            'customerCategory'     => CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON,
            'identificationNumber' => '12345abcde',
            'address'              => (object) ['postalPlace' => 'Paris'],
            'firstName'            => 'Paulchen',
            'lastName'             => 'Panther',
            'customerNumber'       => 'abced12345',
            'salutation'           => CheckoutCustomerEntity::SALUTATION_MR,
            'email'                => 'panther@blakeedwards.com',
            'phone'                => '+49/0800/12345678',
            'mobilePhone'          => '+49/0171/12345678',
            'birthDate'            => '1963-12-19',
            'conversationLanguage' => 'FR',
        ], $testObject->exportData(), 'exported object not valid');
    }
}
