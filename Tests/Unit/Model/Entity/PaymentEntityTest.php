<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class PaymentEntityTest: unit tests for PaymentEntity.
 */
class PaymentEntityTest extends \Arvato\AfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $testData = [
            'type' => \Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_INVOICE
        ];

        $testObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);
        $this->testGetSet($testObject, $testData);
        $this->assertEquals((object)$testData, $testObject->exportData(), 'exported object not valid');
    }
}
