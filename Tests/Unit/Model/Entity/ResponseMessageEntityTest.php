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
 * @author    ©2020 norisk GmbH
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class ResponseMessageEntityTest: Tests for ResponseMessageEntity.
 */
class ResponseMessageEntityTest extends \OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'type' => 'Lorem Invoice',
            'code' => 234,
            'message' => 111,
            'customerFacingMessage' => 'Lorem Ipsum',
            'actionCode' => '222',
            'fieldReference' => '333'
        ];

        $testObject = $this->getSUT();
        $this->testGetSet($testObject, $testData);
        $this->assertEquals((object) $testData, $testObject->exportData(), 'exported object not valid');
    }

    /**
     * SUT generator
     *
     * @return ResponseMessageEntity
     */
    protected function getSUT()
    {
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\ResponseMessageEntity::class);
    }
}
