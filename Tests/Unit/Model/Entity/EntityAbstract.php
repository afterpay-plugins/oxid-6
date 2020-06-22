<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class EntityTest: Base class for the entity tests.
 */
abstract class EntityAbstract extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Tests the setter andd getter methods for consistency.
     *
     * @param Entity $testObject
     * @param mixed[] $testData
     */
    public function testGetSet(= \Arvato\AfterpayModule\Application\Model\Entity\Entity $testObject, $testData)
    {
        foreach ($testData as $key => $value) {
            $setter = $this->getSetterName($key);
            $testObject->$setter($value);
        }

        foreach ($testData as $key => $value) {
            $getter = $this->getGetterName($key);
            if (is_object($value)) {
                $this->assertSame(
                    $value,
                    $testObject->$getter(),
                    $key . ' not equal'
                );
            } else {
                $this->assertEquals(
                    $value,
                    $testObject->$getter(),
                    $key . ' not equal'
                );
            }
        }
    }

    /**
     * Returns the name of the setter method of a property.
     *
     * @param string $property
     * @return string
     */
    protected function getSetterName($property)
    {
        return 'set' . ucfirst($property);
    }

    /**
     * Returns the name of the getter method of a property.
     *
     * @param string $property
     * @return string
     */
    protected function getGetterName($property)
    {
        return 'get' . ucfirst($property);
    }
}
