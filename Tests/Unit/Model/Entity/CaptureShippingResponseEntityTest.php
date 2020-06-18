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
 * Class CaptureShippingResponseEntityTest: Tests for CaptureShippingResponseEntity.
 */
class CaptureShippingResponseEntityTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method getShippingNumber
     */
    public function testGetSetShippingNumber()
    {
        $sut = $this->getSUT();
        $sut->setShippingNumber('Lorem');
        $sutReturn = $sut->getShippingNumber();
        $this->assertEquals('Lorem', $sutReturn);
    }

    /**
     * SUT generator
     *
     * @return CaptureShippingResponseEntity
     */
    protected function getSUT()
    {
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity::class);
    }
}
