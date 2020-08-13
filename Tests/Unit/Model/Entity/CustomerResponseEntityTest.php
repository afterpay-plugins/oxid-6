<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\CustomerResponseEntity;

/**
 * Class CustomerResponseEntityTest: Tests for CustomerResponseEntity.
 */
class CustomerResponseEntityTest extends EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'customerNumber' => 123,
            'firstName'      => 'Jon',
            'lastName'       => 'Doe',
            'addressList'    => ['Lorem', 'Ipsum'],
        ];

        $testObject = $this->getSUT();
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return CustomerResponseEntity
     */
    protected function getSUT()
    {
        return oxNew(CustomerResponseEntity::class);
    }
}
