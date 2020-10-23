<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity;
use stdClass;

/**
 * Class CaptureEntityTest: Tests for CaptureEntity.
 */
class CaptureEntityTest extends EntityAbstract
{

    /**
     * Testing method getOrderDetails
     * Testing method setOrderDetails
     */
    public function testGetAndSetOrderDetails()
    {
        $orderDetails = new stdClass();
        $orderDetails->lorem = 'ipsum';

        $testData = [
            'orderDetails' => $orderDetails,
        ];

        $testObject = $this->getSUT();
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return CaptureEntity
     */
    protected function getSUT()
    {
        return oxNew(CaptureEntity::class);
    }
}
