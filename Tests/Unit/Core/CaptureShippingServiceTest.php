<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Application\Model\Order as AfterpayOrder;
use Arvato\AfterpayModule\Core\CaptureShippingService;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

/**
 * Class CaptureShippingServiceTest: Tests for CaptureShippingService.
 */
class CaptureShippingServiceTest extends UnitTestCase
{
    /**
     * Read DB Fixtures
     */
    public function setUp()
    {
        parent::setUp();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/arvato/afterpay/Tests/Fixtures/orders_setUp.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }

    /**
     * Delete DB Fixtures
     */
    public function tearDown()
    {
        parent::tearDown();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/arvato/afterpay/Tests/Fixtures/generalTearDown.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }

    /**
     * Testing method captureShipping
     *
     * @throws StandardException
     */
    public function testCaptureShippingSuccess()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitcapturedorder');
        $sut = $this->getSutThatWillSucceedCaptureShipping($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $CaptureResponseEntity = $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);

        $this->assertEquals(6, $CaptureResponseEntity->getShippingNumber(), 'Shipping number test failed');

        $this->assertEquals(
            'captured',
            $oxOrder->getAfterpayOrder()->getStatus(),
            'Shipping capture status failed'
        );
    }

    /**
     * Testing method capture - failure
     *
     * @throws StandardException
     */
    public function testCaptureShippingFailure()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitcapturedorder');
        $sut = $this->getSutThatWillFailCaptureShipping($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $CaptureResponseEntity = $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);

        $this->assertNull($CaptureResponseEntity->getShippingNumber());

        $this->assertEquals(
            'captured',
            $oxOrder->getAfterpayOrder()->getStatus()
        );
    }

    /**
     * Testing method capture - failure
     *
     * @throws StandardException
     */
    public function testCaptureShippingException()
    {
        $this->setExpectedException(StandardException::class);

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitnonafterpayorder');
        $sut = $this->getSutThatWillThrowException($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);
    }

    /**
     * Testing method getErrorMessages - Capture fails, Error Message present
     *
     * @throws StandardException
     */
    public function testGetErrorMessagesOnErrors()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitcapturedorder');
        $sut = $this->getSutThatWillFailCaptureShipping($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);
        $errorMessages = $sut->getErrorMessages();
        $this->assertTrue(0 < strlen($errorMessages));
        $this->assertEquals('Some ErrorMessage', $errorMessages);
    }

    /**
     * Testing method getErrorMessages - Capture successful, no Errors
     *
     * @throws StandardException
     */
    public function testGetErrorMessagesOonNoErrors()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitcapturedorder');
        $sut = $this->getSutThatWillSucceedCaptureShipping($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);
        $errorMessages = $sut->getErrorMessages();
        $this->assertEquals('', $errorMessages);
    }

    /**
     * @param stdClass|stdClass[] $response
     * @param Order               $mockOxOrder
     *
     * @return PHPUnit_Framework_MockObject_MockObject|CaptureShippingService Mocked
     */
    protected function getMockedCaptureShippingService($response, Order $mockOxOrder)
    {
        $mockCaptureService =
            $this->getMockBuilder(CaptureShippingService::class)
                 ->setConstructorArgs([$mockOxOrder])
                 ->setMethods(['executeRequestFromOrderData'])
                 ->getMock();

        $mockCaptureService
            ->method('executeRequestFromOrderData')
            ->will($this->returnValue($response));

        return $mockCaptureService;
    }

    /**
     * Return session Mock that tests if reservationId and checkoutId is stored correctly
     *
     * @return PHPUnit_Framework_MockObject_MockObject|Session Mocked
     */
    protected function getMockOxSession()
    {
        return $this->getMockBuilder(Session::class)
                    ->disableOriginalConstructor()
                    ->disableOriginalClone()
                    ->setMethods(['setVariable'])
                    ->getMock();
    }

    /**
     * @param Order|AfterpayOrder $oxOrder
     *
     * @return CaptureShippingService
     */
    protected function getSutThatWillFailCaptureShipping(Order $oxOrder)
    {
        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/arvato/afterpay/Tests/Fixtures/captureShippingFailureResponse.json'
        ));

        // Self-Testing Fixtures:
        $this->assertNotNull($oxOrder->getAfterpayOrder());
        $this->assertEquals('unitcapturedorder', $oxOrder->getAfterpayOrder()->getId());
        $this->assertEquals(
            'captured',
            $oxOrder->getAfterpayOrder()->getStatus(),
            'Self-testing fixture: Failing order has to start out "captured"'
        );

        // End of Self-Test

        return $this->getMockedCaptureShippingService($response, $oxOrder);
    }

    /**
     * @param Order|AfterpayOrder $oxOrder
     *
     * @return CaptureShippingService
     */
    protected function getSutThatWillThrowException(Order $oxOrder)
    {
        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/arvato/afterpay/Tests/Fixtures/captureShippingFailureResponse.json'
        ));

        // Self-Testing Fixtures:
        $this->assertNotNull($oxOrder->getAfterpayOrder());

        // End of Self-Test

        return $this->getMockedCaptureShippingService($response, $oxOrder);
    }

    /**
     * @param Order|AfterpayOrder $oxOrder
     *
     * @return CaptureShippingService
     */
    protected function getSutThatWillSucceedCaptureShipping($oxOrder)
    {

        // Build SUT : Get ResponseData to inject

        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/arvato/afterpay/Tests/Fixtures/captureShippingSuccessResponse.json'
        ));

        // Self-Testing Fixtures:
        $this->assertNotNull($oxOrder->getAfterpayOrder());
        $this->assertEquals('unitcapturedorder', $oxOrder->getAfterpayOrder()->getId());
        $this->assertEquals(
            'captured',
            $oxOrder->getAfterpayOrder()->getStatus(),
            'Self-testing fixture: Succeeding order has to start out "captured"'
        );

        // End of Self-Test

        return $this->getMockedCaptureShippingService($response, $oxOrder);
    }
}
