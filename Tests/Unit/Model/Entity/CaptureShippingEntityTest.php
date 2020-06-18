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
 * Class CaptureShippingEntityTest: Tests for CaptureShippingEntity.
 */
class CaptureShippingEntityTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method gettype
     */
    public function testGetSettype()
    {
        $sut = $this->getSUT();
        $sut->setType('Lorem');
        $sutReturn = $sut->getType();
        $this->assertEquals('Lorem', $sutReturn);
    }

    /**
     * Testing method getshippingCompany
     */
    public function testGetSetShippingCompany()
    {
        $sut = $this->getSUT();
        $sut->setShippingCompany('Lorem');
        $sutReturn = $sut->getShippingCompany();
        $this->assertEquals('Lorem', $sutReturn);
    }

    /**
     * Testing method gettrackingId
     */
    public function testGetSetTrackingId()
    {
        $sut = $this->getSUT();
        $sut->setTrackingId('Lorem');
        $sutReturn = $sut->getTrackingId();
        $this->assertEquals('Lorem', $sutReturn);
    }

    /**
     * SUT generator
     *
     * @return CaptureShippingEntity
     */
    protected function getSUT()
    {
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CaptureShippingEntity::class);
    }
}
