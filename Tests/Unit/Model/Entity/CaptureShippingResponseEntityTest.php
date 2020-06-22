<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

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
        return oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity::class);
    }
}
