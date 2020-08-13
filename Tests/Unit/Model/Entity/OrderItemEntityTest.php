<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity;

/**
 * Class OrderItemEntityTest: unit tests for OrderItemEntity.
 */
class OrderItemEntityTest extends EntityAbstract
{
    /**
     * Tests the data container.
     */
    public function testDataContainer()
    {
        $testData = [
            'productId'               => 'oxid123',
            'description'             => 'von aussen einstellbarer Innenspiegel',
            'quantity'                => 3,
            'grossUnitPrice'          => 1.2,
            'groupId'                 => 'abcde12345',
            'netUnitPrice'            => 1.1,
            'unitCode'                => 'tons',
            'vatCategory'             => OrderItemEntity::VAT_CATEGORY_MIDDLE,
            'vatPercent'              => 19,
            'vatAmount'               => 10,
            'imageUrl'                => 'http://www.oxid-esales.com/Innenspiegel.jpg',
            'googleProductCategoryId' => '12345abcde',
            'googleProductCategory'   => 'Autoteile',
            'merchantProductType'     => 'good merchant',
            'lineNumber'              => 1,
            'discountAmount'          => 5,
            'productUrl'              => 'http://www.oxid-esales.com/shop/Innenspiegel',
            'marketPlaceSellerId'     => '12345abcde',
        ];

        $testObject = oxNew(OrderItemEntity::class);
        $this->getSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }
}
