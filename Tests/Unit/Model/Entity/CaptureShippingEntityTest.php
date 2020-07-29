<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

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
        return oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingEntity::class);
    }
}
