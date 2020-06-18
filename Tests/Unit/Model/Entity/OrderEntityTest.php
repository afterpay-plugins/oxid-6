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
 * Class OrderEntityTest: unit tests for OrderEntity.
 */
class OrderEntityTest extends \OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
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
            'discountAmount'          => 5
        ];

        $testObject = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderEntity::class);
        $this->testGetSet($testObject, $testData);
        $this->assertEquals((object)$testData, $testObject->exportData(), 'exported object not valid');
    }
}
