<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Application\Model\Order as ArvatoOrder;
use Arvato\AfterpayModule\Core\CaptureService;
use Arvato\AfterpayModule\Core\WebServiceClient;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

/**
 * Class CaptureServiceTest: Tests for CaptureService.
 */
class CaptureServiceTest extends UnitTestCase
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
     * Testing method capture - success
     */
    public function testCaptureSuccess()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');
        $sut = $this->getSutThatWillSucceedCapture($oxOrder);

        $CaptureResponseEntity = $sut->capture('SomeApiKey');

        $this->assertEquals(123.45, $CaptureResponseEntity->getCapturedAmount());
        $this->assertEquals(123.45, $CaptureResponseEntity->getAuthorizedAmount());
        $this->assertEquals(0, $CaptureResponseEntity->getRemainingAuthorizedAmount());

        $this->assertEquals(
            'captured',
            $oxOrder->getAfterpayOrder()->getStatus()
        );
    }

    /**
     * Testing method capture - failure
     */
    public function testCaptureFailure()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');
        $sut = $this->getSutThatWillFailCapture($oxOrder);
        $CaptureResponseEntity = $sut->capture('SomeApiKey');

        $this->assertNull($CaptureResponseEntity->getCapturedAmount());
        $this->assertNull($CaptureResponseEntity->getAuthorizedAmount());
        $this->assertNull($CaptureResponseEntity->getRemainingAuthorizedAmount());

        //
        $this->assertEquals(
            'authorized',
            $oxOrder->getAfterpayOrder()->getStatus(),
            'Assert that order is not set to captures-status on failure'
        );
    }

    /**
     * Testing method getErrorMessages - Capture fails, Error Message present
     */
    public function testGetErrorMessagesOnErrors()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');
        $sut = $this->getSutThatWillFailCapture($oxOrder);
        $sut->capture('SomeApiKey');
        $errorMessages = $sut->getErrorMessages();
        $this->assertTrue(0 < strlen($errorMessages));
        $this->assertEquals('Some ErrorMessage', $errorMessages);
    }

    /**
     * Testing method getErrorMessages - Capture Successfull, no Errors
     */
    public function testGetErrorMessagesOnNoErrors()
    {
        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');
        $sut = $this->getSutThatWillSucceedCapture($oxOrder);
        $sut->capture('SomeApiKey');
        $errorMessages = $sut->getErrorMessages();
        $this->assertEquals('', $errorMessages);
    }

    public function testExecuteRequestFromOrderData()
    {
        /** @var CaptureService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(CaptureService::class)
                 ->disableOriginalConstructor()
                 ->setMethods(['getCaptureDataForApi', 'getCaptureClientForApi'])
                 ->getMock();

        // Client

        $mockClient =
            $this->getMockBuilder(WebServiceClient::class)
                 ->setMethods(['execute'])
                 ->getMock();

        $mockClient
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue('###OK###'));

        // SUT

        $sut
            ->expects($this->once())
            ->method('getCaptureClientForApi')
            ->will($this->returnValue($mockClient));

        $sut
            ->expects($this->once())
            ->method('getCaptureDataForApi')
            ->will($this->returnValue('###OK###'));

        // run
        $this->assertEquals('###OK###', $sut->testexecuteRequestFromOrderData('SomeApiKey'));
    }

    /**
     * @param stdClass|stdClass[] $response
     * @param Order               $mockOxOrder
     *
     * @return CaptureService|PHPUnit_Framework_MockObject_MockObject Mocked
     */
    protected function getMockedCaptureService($response, Order $mockOxOrder)
    {
        $mockCaptureService =
            $this->getMockBuilder(CaptureService::class)
                 ->setConstructorArgs([$mockOxOrder])
                 ->setMethods(['executeRequestFromOrderData'])
                 ->getMock();

        $mockCaptureService
            ->expects($this->once())
            ->method('executeRequestFromOrderData')
            ->will($this->returnValue($response));

        return $mockCaptureService;
    }


    /**
     * Return session Mock that tests if reservationId and checkoutId is stored correctly
     *
     * @return Session|PHPUnit_Framework_MockObject_MockObject Mocked
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
     * @param Order|ArvatoOrder $oxOrder
     * @return CaptureService
     */
    protected function getSutThatWillFailCapture(Order $oxOrder)
    {
        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/arvato/afterpay/Tests/Fixtures/captureErrorResponse.json'
        ));

        // Self-Testing Fixtures:
        $this->assertNotNull($oxOrder->getAfterpayOrder());
        $this->assertEquals('unitauthorizedorder', $oxOrder->getAfterpayOrder()->getId());
        $this->assertEquals(
            'authorized',
            $oxOrder->getAfterpayOrder()->getStatus(),
            'Self-testing fixture: Failing order has to start out "authorized"'
        );

        // End of Self-Test

        return $this->getMockedCaptureService($response, $oxOrder);
    }

    /**
     * @param $oxOrder
     *
     * @return CaptureService
     */
    protected function getSutThatWillSucceedCapture($oxOrder)
    {
        // Build SUT : Get ResponseData to inject

        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/arvato/afterpay/Tests/Fixtures/captureSuccessResponse.json'
        ));

        // Self-Testing Fixtures:
        $this->assertNotNull($oxOrder->getAfterpayOrder());
        $this->assertEquals('unitauthorizedorder', $oxOrder->getAfterpayOrder()->getId());
        $this->assertEquals(
            'authorized',
            $oxOrder->getAfterpayOrder()->getStatus(),
            'Self-testing fixture: Succeeding order has to start out "authorized"'
        );

        // End of Self-Test

        return $this->getMockedCaptureService($response, $oxOrder);
    }
}
