<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class AddressDataProviderTest: Tests for AddressDataProvider.
 */
class AddressDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testGetUserAddress()
    {
        $testData = [
            'oxcountryid' => DatabaseProvider::getDb()->getOne("select oxid from oxcountry where oxisoalpha3 = 'DEU'"),
            'oxzip' => '06108',
            'oxstreet' => 'Gr. Ulrichstr.',
            'oxstreetnr' => '21',
            'oxaddinfo' => '4. Etage',
            'oxcity' => 'Halle',
            'oxcompany' => 'OXID eSales Standort Halle'
        ];
        $testAddressData = [
            'oxcountryid' => DatabaseProvider::getDb()->getOne("select oxid from oxcountry where oxisoalpha3 = 'CHE'"),
            'oxzip' => '79098',
            'oxstreet' => 'Bertoldstraße',
            'oxstreetnr' => '48',
            'oxaddinfo' => '5. Etage',
            'oxcity' => 'Freiburg',
            'oxcompany' => 'OXID eSales'
        ];

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->assign($testData);

        $address = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
        $address->assign($testAddressData);
        $address->setUser($user);

        $testObject = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AddressDataProvider::class);
        $address = $testObject->getUserAddress($user);

        $this->assertEquals('DE', $address->getCountryCode());
        $this->assertEquals($testData['oxzip'], $address->getPostalCode());
        $this->assertEquals($testData['oxstreet'], $address->getStreet());
        $this->assertEquals($testData['oxstreetnr'], $address->getStreetNumber());
        $this->assertEquals($testData['oxaddinfo'], $address->getStreetNumberAdditional());
        $this->assertEquals($testData['oxcity'], $address->getPostalPlace());
        $this->assertEquals($testData['oxcompany'], $address->getCareOf());
    }

    public function testgetDeliveryAddressAddressfound()
    {
        $testData = [
            'oxcountryid' => DatabaseProvider::getDb()->getOne("select oxid from oxcountry where oxisoalpha3 = 'DEU'"),
            'oxzip' => '06108',
            'oxstreet' => 'Gr. Ulrichstr.',
            'oxstreetnr' => '21',
            'oxaddinfo' => '4. Etage',
            'oxcity' => 'Halle',
            'oxcompany' => 'OXID eSales Standort Halle'
        ];
        $testAddressData = [
            'oxcountryid' => DatabaseProvider::getDb()->getOne("select oxid from oxcountry where oxisoalpha3 = 'CHE'"),
            'oxzip' => '79098',
            'oxstreet' => 'Bertoldstraße',
            'oxstreetnr' => '48',
            'oxaddinfo' => '5. Etage',
            'oxcity' => 'Freiburg',
            'oxcompany' => 'OXID eSales'
        ];

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->assign($testData);

        $address = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
        $address->assign($testAddressData);
        $address->setUser($user);

        $testObject = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AddressDataProvider::class);
        $address = $testObject->getDeliveryAddress($user);

        $this->assertEquals('DE', $address->getCountryCode());
        $this->assertEquals($testData['oxzip'], $address->getPostalCode());
        $this->assertEquals($testData['oxstreet'], $address->getStreet());
        $this->assertEquals($testData['oxstreetnr'], $address->getStreetNumber());
        $this->assertEquals($testData['oxaddinfo'], $address->getStreetNumberAdditional());
        $this->assertEquals($testData['oxcity'], $address->getPostalPlace());
        $this->assertEquals($testData['oxcompany'], $address->getCareOf());
    }

    public function testgetDeliveryAddressAddressnotfound()
    {
        $testData = [
            'oxcountryid' => DatabaseProvider::getDb()->getOne("select oxid from oxcountry where oxisoalpha3 = 'DEU'"),
            'oxzip' => '06108',
            'oxstreet' => 'Gr. Ulrichstr.',
            'oxstreetnr' => '21',
            'oxaddinfo' => '4. Etage',
            'oxcity' => 'Halle',
            'oxcompany' => 'OXID eSales Standort Halle'
        ];


        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->assign($testData);


        $testObject = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AddressDataProvider::class);
        $address = $testObject->getDeliveryAddress($user);

        $this->assertEquals('DE', $address->getCountryCode());
        $this->assertEquals($testData['oxzip'], $address->getPostalCode());
        $this->assertEquals($testData['oxstreet'], $address->getStreet());
        $this->assertEquals($testData['oxstreetnr'], $address->getStreetNumber());
        $this->assertEquals($testData['oxaddinfo'], $address->getStreetNumberAdditional());
        $this->assertEquals($testData['oxcity'], $address->getPostalPlace());
        $this->assertEquals($testData['oxcompany'], $address->getCareOf());
    }
}
