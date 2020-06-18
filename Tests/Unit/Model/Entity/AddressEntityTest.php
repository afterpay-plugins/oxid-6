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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class AddressEntityTest: unit tests for AddressEntity.
 */
class AddressEntityTest extends \OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $testData = [
            'countryCode'               => 'DE',
            'postalCode'                => '12345',
            'street'                    => 'Musterstrasse',
            'streetNumber'              => '40a',
            'streetNumberAdditional'    => '4. Etage',
            'postalPlace'               => 'Berlin',
            'careOf'                    => 'Mr. Blue'
        ];

        $testObject = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\AddressEntity::class);
        $this->testGetSet($testObject, $testData);

        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }
}
