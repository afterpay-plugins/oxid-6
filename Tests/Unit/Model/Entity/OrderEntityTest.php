<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\OrderEntity;

/**
 * Class OrderEntityTest: unit tests for OrderEntity.
 */
class OrderEntityTest extends EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {

        $testData = [
            'totalGrossAmount'        => 11.11,
            'currency'                => 'SomeCurrency',
            'items'                   => ['some', 'items'],
            'number'                  => 'order-08-15',
            'totalNetAmount'          => 22.34,
            'imageUrl'                => 'http://www.oxid-esales.com/Innenspiegel.jpg',
            'googleAnalyticsUserId'   => 'google123',
            'googleAnalyticsClientId' => 'google456',
            'discountAmount'          => 5,
        ];

        $testObject = oxNew(OrderEntity::class);
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }
}
