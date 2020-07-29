<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class CaptureEntityTest: Tests for CaptureEntity.
 */
class CaptureEntityTest extends \Arvato\AfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{

    /**
     * Testing method getOrderDetails
     * Testing method setOrderDetails
     */
    public function testGetAndSetOrderDetails()
    {
        $orderDetails = new \stdClass();
        $orderDetails->lorem = 'ipsum';

        $testData = [
            'orderDetails' => $orderDetails
        ];

        $testObject = $this->getSUT();
        $this->testGetSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return CaptureEntity
     */
    protected function getSUT()
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity::class);
    }
}
