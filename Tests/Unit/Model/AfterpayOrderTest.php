<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model;

use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class AfterpayOrderTest: Tests for AfterpayOrder.
 */
class AfterpayOrderTest extends UnitTestCase
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
     *
     * @noinspection PhpUndefinedFieldInspection
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
     *
     * @throws StandardException
     * @throws StandardException
     * @noinspection PhpUndefinedFieldInspection
     */
    public function testSetStatusLegalStatus()
    {
        $sut = $this->getSUT();
        $sut->setStatus('authorized');
        $this->assertEquals('authorized', $sut->arvatoafterpayafterpayorder__apstatus->value);
    }

    /**
     * @throws StandardException
     */
    public function testSetStatusIllegalStatus()
    {
        $this->setExpectedException(StandardException::class);
        $sut = $this->getSUT();
        $sut->setStatus('FooBar!');
    }

    /**
     * Testing method getStatus
     *
     * @throws StandardException
     */
    public function testGetStatus()
    {
        $sut = $this->getSUT();
        $sut->setStatus('authorized');
        $this->assertEquals('authorized', $sut->getStatus());
    }

    /**
     * SUT generator
     *
     * @return AfterpayOrder
     */
    protected function getSUT()
    {
        $dummyOxOrder = oxNew(Order::class);
        /** @var AfterpayOrder $dummyAfterpayOrder */
        $dummyAfterpayOrder = oxNew(AfterpayOrder::class, $dummyOxOrder);

        return $dummyAfterpayOrder;
    }
}
