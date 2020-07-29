<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class CustomerResponseEntityTest: Tests for CustomerResponseEntity.
 */
class CustomerResponseEntityTest extends \Arvato\AfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
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
            'addressList'    => ['Lorem', 'Ipsum']
        ];

        $testObject = $this->getSUT();
        $this->testGetSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return CaptureResponseEntity
     */
    protected function getSUT()
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CustomerResponseEntity::class);
    }
}
