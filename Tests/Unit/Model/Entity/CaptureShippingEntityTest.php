<?php

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingEntity;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class CaptureShippingEntityTest: Tests for CaptureShippingEntity.
 */
class CaptureShippingEntityTest extends UnitTestCase
{
    /**
     * Testing method getType
     */
    public function testGetSettype()
    {
        $sut = $this->getSUT();
        $sut->setType('Lorem');
        $sutReturn = $sut->getType();
        $this->assertEquals('Lorem', $sutReturn);
    }

    /**
     * Testing method getShippingCompany
     */
    public function testGetSetShippingCompany()
    {
        $sut = $this->getSUT();
        $sut->setShippingCompany('Lorem');
        $sutReturn = $sut->getShippingCompany();
        $this->assertEquals('Lorem', $sutReturn);
    }

    /**
     * Testing method getTrackingId
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
        return oxNew(CaptureShippingEntity::class);
    }
}
