<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class AddressEntityTest: unit tests for AddressEntity.
 */
class AddressEntityTest extends \Arvato\AfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
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

        $testObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AddressEntity::class);
        $this->testGetSet($testObject, $testData);

        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }
}
