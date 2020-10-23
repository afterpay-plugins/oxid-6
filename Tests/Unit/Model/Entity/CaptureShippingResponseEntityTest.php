<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class CaptureShippingResponseEntityTest: Tests for CaptureShippingResponseEntity.
 */
class CaptureShippingResponseEntityTest extends UnitTestCase
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
        return oxNew(CaptureShippingResponseEntity::class);
    }
}
