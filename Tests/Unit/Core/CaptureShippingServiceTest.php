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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class CaptureShippingServiceTest: Tests for CaptureShippingService.
 */
class CaptureShippingServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Read DB Fixtures
     */
    public function setUp()
    {
        parent::setUp();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/oxps/arvatoafterpay/Tests/Fixtures/orders_setUp.sql');
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
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/oxps/arvatoafterpay/Tests/Fixtures/generalTearDown.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }

    /**
     * Testing method captureShipping
     */
    public function testCaptureShippingSuccess()
    {
        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitcapturedorder');
        $sut = $this->getSutThatWillSucceedCaptureShipping($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $CaptureResponseEntity = $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);

        $this->assertEquals(6, $CaptureResponseEntity->getShippingNumber());

        $this->assertEquals(
            'captured',
            $oxOrder->getAfterpayOrder()->getStatus()
        );
    }

    /**
     * Testing method capture - failure
     */
    public function testCaptureShippingFailure()
    {
        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
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
     */
    public function testCaptureShippingException()
    {
        $this->setExpectedException(\OxidEsales\Eshop\Core\Exception\StandardException::class);

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitnonafterpayorder');
        $sut = $this->getSutThatWillThrowException($oxOrder);
        $trackingID = 'tr12345';
        $shippingCompany = 'ACME';
        $CaptureResponseEntity = $sut->captureShipping($trackingID, 'SomeApiKey', $shippingCompany);
    }

    /**
     * Testing method getErrorMessages - Capture fails, Error Message present
     */
    public function testGetErrorMessagesOnErrors()
    {
        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
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
     * Testing method getErrorMessages - Capture Successfull, no Errors
     */
    public function testGetErrorMessagesOonNoErrors()
    {
        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
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
     * @param oxOrder $mockOxOrder
     *
     * @return CaptureService Mocked
     */
    protected function getMockedCaptureShippingService($response, \OxidEsales\Eshop\Application\Model\Order $mockOxOrder)
    {
        $mockCaptureService =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CaptureShippingService::class)
                ->setConstructorArgs([$mockOxOrder])
                ->setMethods(array('executeRequestFromOrderData'))
                ->getMock();

        $mockCaptureService
            ->method('executeRequestFromOrderData')
            ->will($this->returnValue($response));

        return $mockCaptureService;
    }

    /**
     * Return session Mock that tests if reservationId and checkoutId is stored correctly
     *
     * @return oxSession Mocked
     */
    protected function getMockOxSession()
    {
        return $this->getMockBuilder(\OxidEsales\Eshop\Core\Session::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->setMethods(array('setVariable'))
                ->getMock();
    }

    /**
     * @param oxOrder $orOrder
     *
     * @return CaptureShippingService
     */
    protected function getSutThatWillFailCaptureShipping(\OxidEsales\Eshop\Application\Model\Order $oxOrder)
    {
        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/oxps/arvatoafterpay/Tests/Fixtures/captureShippingFailureResponse.json'
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
     * @param oxOrder $oxOrder
     *
     * @return CaptureShippingService
     */
    protected function getSutThatWillThrowException(\OxidEsales\Eshop\Application\Model\Order $oxOrder)
    {
        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/oxps/arvatoafterpay/Tests/Fixtures/captureShippingFailureResponse.json'
        ));

        // Self-Testing Fixtures:
        $this->assertNotNull($oxOrder->getAfterpayOrder());
        // End of Self-Test

        return $this->getMockedCaptureShippingService($response, $oxOrder);
    }

    /**
     * @param $oxOrder
     *
     * @return CaptureShippingService
     */
    protected function getSutThatWillSucceedCaptureShipping($oxOrder)
    {

        // Build SUT : Get ResponseData to inject

        $response = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/oxps/arvatoafterpay/Tests/Fixtures/captureShippingSuccessResponse.json'
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
