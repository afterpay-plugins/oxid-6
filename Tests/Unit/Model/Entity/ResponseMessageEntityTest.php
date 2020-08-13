<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\ResponseMessageEntity;

/**
 * Class ResponseMessageEntityTest: Tests for ResponseMessageEntity.
 */
class ResponseMessageEntityTest extends EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'type'                  => 'Lorem Invoice',
            'code'                  => 234,
            'message'               => 111,
            'customerFacingMessage' => 'Lorem Ipsum',
            'actionCode'            => '222',
            'fieldReference'        => '333',
        ];

        $testObject = $this->getSUT();
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return ResponseMessageEntity
     */
    protected function getSUT()
    {
        return oxNew(ResponseMessageEntity::class);
    }
}
