<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category  module
 * @package   afterpay
 * @author    OXID Professional services
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class OrderItemEntityTest: unit tests for OrderItemEntity.
 */
class OrderItemEntityTest extends \OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
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
            'vatCategory'             => \OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderItemEntity::VAT_CATEGORY_MIDDLE,
            'vatPercent'              => 19,
            'vatAmount'               => 10,
            'imageUrl'                => 'http://www.oxid-esales.com/Innenspiegel.jpg',
            'googleProductCategoryId' => '12345abcde',
            'googleProductCategory'   => 'Autoteile',
            'merchantProductType'     => 'good merchant',
            'lineNumber'              => 1,
            'discountAmount'          => 5,
            'productUrl'              => 'http://www.oxid-esales.com/shop/Innenspiegel',
            'marketPlaceSellerId'     => '12345abcde'
        ];

        $testObject = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderItemEntity::class);
        $this->testGetSet($testObject, $testData);
        $this->assertEquals((object)$testData, $testObject->exportData(), 'exported object not valid');
    }
}
