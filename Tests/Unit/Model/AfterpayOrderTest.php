<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class AfterpayOrderTest: Tests for AfterpayOrder.
 */
class AfterpayOrderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method fillBySession
     */
    public function testFillBySession()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('arvatoAfterpayReservationId', 'reservation123');
        $oxSession->setVariable('arvatoAfterpayCheckoutId', 'checkout123');
        $sut = $this->getSUT();
        $sut->fillBySession($oxSession);

        $this->assertEquals('reservation123', $sut->arvatoafterpayafterpayorder__apreservationid->value);
        $this->assertEquals('checkout123', $sut->arvatoafterpayafterpayorder__apcheckoutid->value);
        $this->assertEquals('authorized', $sut->arvatoafterpayafterpayorder__apstatus->value);
    }

    /**
     * Testing method setStatus
     */
    public function testSetStatusLegalStatus()
    {
        $sut = $this->getSUT();
        $sut->setStatus('authorized');
        $this->assertEquals('authorized', $sut->arvatoafterpayafterpayorder__apstatus->value);
    }

    /**
     * Testing method setStatus
     * @expectedException \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    public function testSetStatusIllegalStatus()
    {
        $sut = $this->getSUT();
        $sut->setStatus('FooBar!');
    }

    /**
     * Testing method getStatus
     */
    public function testGetStatus()
    {
        $sut = $this->getSUT();
        $sut->setStatus('authorized');
        $this->assertEquals('authorized', $sut->getStatus('authorized'));
    }

    /**
     * SUT generator
     *
     * @return AfterpayOrder
     */
    protected function getSUT()
    {
        $dummyOxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        return oxNew(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::class, $dummyOxOrder);
    }
}
