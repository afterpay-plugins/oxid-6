<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity;

/**
 * Class PaymentEntityTest: unit tests for PaymentEntity.
 */
class PaymentEntityTest extends EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $testData = [
            'type' => PaymentEntity::TYPE_INVOICE,
        ];

        $testObject = oxNew(PaymentEntity::class);
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }
}
