<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureResponseEntity;

/**
 * Class CaptureResponseEntityTest: Tests for CaptureResponseEntity.
 */
class CaptureResponseEntityTest extends EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'capturedAmount'            => 123,
            'authorizedAmount'          => 234,
            'remainingAuthorizedAmount' => 111,
            'captureNumber'             => 'Lorem Ipsum',
        ];

        $testObject = $this->getSUT();
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return CaptureResponseEntity
     */
    protected function getSUT()
    {
        return oxNew(CaptureResponseEntity::class);
    }
}
