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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class OrderAfterpayTest: Tests for OrderAfterpay.
 */
class OrderAfterpayTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Test that an afterpay order is correctly loaded and given to smarty
     */
    public function testRenderIsAfterpayOrder()
    {
        $oxID = 'unitcapturedorder';
        $sut = $this->getSUT($oxID);

        $this->assertEquals(
            'order_afterpay.tpl',
            $sut->render()
        );

        $aViewData = $sut->getViewData();

        $this->assertTrue(isset($aViewData['oOrder']), 'isset oOrder');

        if (isset($aViewData['oOrder'])) {
            $this->assertEquals(
                $oxID,
                $aViewData['oOrder']->getId()
            );
        }

        $this->assertTrue(isset($aViewData['oAfterpayOrder']));
        if (isset($aViewData['oAfterpayOrder'])) {
            $this->assertEquals(
                $oxID,
                $aViewData['oAfterpayOrder']->getId()
            );
        }

        $this->assertFalse(isset($aViewData['sMessage']));
    }

    /**
     * Test that a non-afterpay-order is correctly marked
     */
    public function testRenderIsNotRecordedAfterpayOrder()
    {
        $oxID = 'unitnonrecordedafterpayorder';
        $sut = $this->getSUT($oxID);

        $this->assertEquals(
            'order_afterpay.tpl',
            $sut->render()
        );

        $aViewData = $sut->getViewData();

        $this->assertFalse(isset($aViewData['oOrder']), 'isset aOrder');
        $this->assertFalse(isset($aViewData['oAfterpayOrder']), 'isset aAfterpayOrder');
        $this->assertTrue(isset($aViewData['sMessage']), 'isset sMessage');
    }

    /**
     * Test that a non-afterpay-order is correctly marked
     */
    public function testRenderIsNotAfterpayOrder()
    {
        $oxID = 'unitnonafterpayorder';
        $sut = $this->getSUT($oxID);

        $this->assertEquals(
            'order_afterpay.tpl',
            $sut->render()
        );

        $aViewData = $sut->getViewData();

        $this->assertFalse(isset($aViewData['oOrder']));
        $this->assertFalse(isset($aViewData['oAfterpayOrder']));
        $this->assertTrue(isset($aViewData['sMessage']));
    }

    public function testGetEditObject()
    {
        $oxID = 'unitcapturedorder';
        $sut = $this->getSUT($oxID);
        $editObject = $sut->getEditObject();
        $this->assertEquals('unitcapturedorder', $editObject->getId());
    }

    public function testCaptureDidCapture()
    {
        $sut = $this->getCaptureMockedSut(132.45);
        $aViewData = $sut->getViewData();
        $this->assertFalse(isset($aViewData['aErrorMessages']));
        $this->assertTrue(isset($aViewData['oCaptureSuccess']));
    }

    public function testCaptureDidNotCaptureServiceLevelError()
    {
        $sut = $this->getCaptureMockedSut(0, true);
        $aViewData = $sut->getViewData();
        $this->assertEquals('ServiceLevelError', $aViewData['aErrorMessages']);
        $this->assertFalse(isset($aViewData['oCaptureSuccess']));
    }

    public function testCaptureDidNotCaptureResponseLevelError()
    {
        $sut = $this->getCaptureMockedSut(0, false);
        $aViewData = $sut->getViewData();
        $this->assertEquals(['ResponseLevelError'], $aViewData['aErrorMessages']);
        $this->assertFalse(isset($aViewData['oCaptureSuccess']));
    }

    public function testCaptureshippingSuccessfull()
    {
        $sut = $this->getCaptureshippingMockedSut(123);
        $aViewData = $sut->getViewData();
        $this->assertFalse(isset($aViewData['aErrorMessages']), 'error messages set');
        $this->assertTrue(isset($aViewData['oCaptureShippingSuccess']), 'capture shipping success set');
    }

    public function testCaptureshippingUnsuccessfullServiceLevelError()
    {
        $sut = $this->getCaptureshippingMockedSut(null, true);
        $aViewData = $sut->getViewData();
        $this->assertEquals('ServiceLevelError', $aViewData['aErrorMessages'], 'error messages set');
        $this->assertFalse(isset($aViewData['oCaptureShippingSuccess']), 'capture shipping success set');
    }

    public function testCaptureshippingUnsuccessfullResponseLevelError()
    {
        $sut = $this->getCaptureshippingMockedSut(null, false);
        $aViewData = $sut->getViewData();
        $this->assertEquals(['ResponseLevelError'], $aViewData['aErrorMessages'], 'error messages set');
        $this->assertFalse(isset($aViewData['oCaptureShippingSuccess']), 'capture shipping success set');
    }

    public function testrefundSuccessfull()
    {
        $sut = $this->getRefundMockedSut([800012345]);
        $aViewData = $sut->getViewData();
        $this->assertFalse(isset($aViewData['aErrorMessages']), 'error messages set');
        $this->assertTrue(isset($aViewData['oRefundSuccess']), 'refund success set');
    }

    public function testrefundUnsuccessfull()
    {
        $sut = $this->getRefundMockedSut(null);
        $aViewData = $sut->getViewData();
        $this->assertTrue(isset($aViewData['aErrorMessages']), 'error messages set');
        $this->assertFalse(isset($aViewData['oRefundSuccess']), 'refund success set');
    }

    /**
     * @expectedException \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    public function testrefundException()
    {

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('getEditObject'))
            ->getMock();

        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));

        $sut->refund(['lorem', 'ipsum'], ['lorem', 'ipsum']);
    }

    public function testgetDefaultShippingCompany()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiDefaultShippingCompany', 'ACME');
        $this->assertEquals(
            'ACME',
            $this->getSUT('lorem')->getDefaultShippingCompany()
        );
    }

    public function testgetDefaultRefundDescription()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiDefaultRefundDescription', 'loremIpsum');
        $this->assertEquals(
            'loremIpsum',
            $this->getSUT('lorem')->getDefaultRefundDescription()
        );
    }

    public function testgetRefundVatPercentagesOneVatOnly()
    {

        $orderarticle_vat19 = new \stdClass();
        $orderarticle_vat19->oxorderarticles__oxvat = new \OxidEsales\Eshop\Core\Field(19);

        $aOrderArticles = [$orderarticle_vat19, $orderarticle_vat19];
        $this->assertEquals(
            [19],
            $this->getSUT('lorem')->getRefundVatPercentages($aOrderArticles)
        );
    }

    public function testsmartyAssignOrderDetails()
    {

        $article = new \stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article
        ];

        $response = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getOrderItems'))
            ->getMock();
        $response->method('getOrderItems')
            ->will($this->returnValue($items));

        $service = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CaptureService ::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getOrderDetails'))
            ->getMock();
        $service->method('getOrderDetails')
            ->will($this->returnValue($response));

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var $sut OrderAfterpay
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('getOrderDetailsService', 'getEditObject'))
            ->getMock();
        $sut->method('getOrderDetailsService')
            ->will($this->returnValue($service));
        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));

        $sut->smartyAssignOrderDetails();

        $aViewData = $sut->getViewData();

        $this->assertEquals(
            $items,
            $aViewData['aArvatoAllOrderItems']
        );
    }

    public function testorderitemactionCasecapture()
    {

        $sut = $this->getOrderItemActionSUT();

        $sut->method('getFromRequest')
            ->will($this->returnValue('capture'));

        $this->assertEquals('c', $sut->orderitemaction());
    }

    public function testorderitemactionCaserefund()
    {

        $sut = $this->getOrderItemActionSUT();

        $sut->method('getFromRequest')
            ->will($this->returnValue('refund'));

        $this->assertEquals('r', $sut->orderitemaction());
    }

    public function testorderitemactionCasevoid()
    {

        $sut = $this->getOrderItemActionSUT();

        $sut->method('getFromRequest')
            ->will($this->returnValue('void'));

        $this->assertEquals('v', $sut->orderitemaction());
    }

    protected function getOrderItemActionSUT()
    {

        $service = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CaptureService ::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getOrderDetails'))
            ->getMock();
        $service->method('getOrderDetails')
            ->will($this->returnValue(null));

        /**
         * @var $sut OrderAfterpay
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array(
                'getFromRequest',
                'getOrderDetailsService',
                'orderitemactionCapture',
                'orderitemactionRefund',
                'orderitemactionVoid'
            ))
            ->getMock();

        $sut->method('getOrderDetailsService')
            ->will($this->returnValue($service));

        $sut->method('orderitemactionCapture')
            ->will($this->returnValue('c'));

        $sut->method('orderitemactionRefund')
            ->will($this->returnValue('r'));

        $sut->method('orderitemactionVoid')
            ->will($this->returnValue('v'));

        return $sut;
    }

    public function testorderitemactionCapture()
    {

        $article = new \stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article
        ];

        $response = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getOrderItems'))
            ->getMock();
        $response->method('getOrderItems')
            ->will($this->returnValue($items));
        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('capture'))
            ->getMock();
        $sut->expects($this->once())
            ->method('capture')
            ->with($this->equalTo($items))// This is the assertion
            ->will($this->returnValue(null));

        $sut->orderitemactionCapture($response, $items);
    }

    public function testorderitemactionRefund()
    {

        $article = new \stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article
        ];

        $response = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOrderItems'])
            ->getMock();
        $response->method('getOrderItems')
            ->will($this->returnValue($items));
        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(['refund', 'getFromRequest'])
            ->getMock();
        $sut->expects($this->once())
            ->method('getFromRequest')
            ->with('captureNo')// This is the assertion
            ->will($this->returnValue(123));
        $sut->expects($this->once())
            ->method('refund')
            ->with(null, $this->equalTo($items), 123)// This is the assertion
            ->will($this->returnValue(null));

        $sut->orderitemactionRefund($response, $items);
    }

    public function testorderitemactionVoid()
    {

        $article = new \stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article
        ];

        $response = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getOrderItems'))
            ->getMock();
        $response->method('getOrderItems')
            ->will($this->returnValue($items));
        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('void'))
            ->getMock();

        $sut->expects($this->once())
            ->method('void')
            ->with($this->equalTo($items))// This is the assertion
            ->will($this->returnValue(null));

        $sut->orderitemactionVoid($response, $items);
    }

    public function testvoidSuccess()
    {

        $response = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\VoidResponseEntity::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->setMethods(array('getTotalAuthorizedAmount'))
            ->getMock();
        $response->expects($this->once())
            ->method('getTotalAuthorizedAmount')
            ->will($this->returnValue(123));

        $mockVoidServie = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\RefundService::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->setMethods(array('void'))
            ->getMock();
        $mockVoidServie->expects($this->once())
            ->method('void')
            ->will($this->returnValue($response));

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('getEditObject','getVoidPaymentService'))
            ->getMock();

        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));

        $sut->method('getVoidPaymentService')
            ->will($this->returnValue($mockVoidServie));

        $sut->void();

        $this->assertEquals(123, $sut->getViewData()['bVoidSuccessAuthAmountLeft']);
    }

    /**
     * Testing method FormatPrice
     */
    public function testFormatPrice()
    {
        $dPrice = 123.45;
        $this->assertEquals(
            Registry::getLang()->formatCurrency($dPrice),
            $this->getSUT('unitcapturedorder')->formatPrice($dPrice)
        );
    }

    /**
     * @param string $oxOrderId
     *
     * @return OrderAfterpay
     */
    protected function getSUT($oxOrderId)
    {
        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('smartyAssignOrderDetails'))
            ->getMock();
        $sut->method('smartyAssignOrderDetails')
            ->will($this->returnValue(null));
        $sut->setEditObjectId($oxOrderId);
        return $sut;
    }

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
     * @param $capturedAmount
     * @param $bErrorsAreServiceLevel
     *
     * @return OrderAfterpay
     */
    protected function getCaptureMockedSut($capturedAmount, $bErrorsAreServiceLevel = true)
    {
        $mockCaptureResponseEntity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CaptureResponseEntity::class);
        $mockCaptureResponseEntity->setCapturedAmount($capturedAmount);
        $mockCaptureResponseEntity->setErrors(['ResponseLevelError']);

        $mockCaptureServie = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CaptureService::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->setMethods(array('capture', 'getErrorMessages'))
            ->getMock();
        $mockCaptureServie->expects($this->once())
            ->method('capture')
            ->will($this->returnValue($mockCaptureResponseEntity));

        // If there is a captured amount getErrorMessages() is not to be called
        $errorAccesses = $capturedAmount ? $this->never() : $this->once();
        $mockCaptureServie->expects($errorAccesses)
            ->method('getErrorMessages')
            ->will($this->returnValue($bErrorsAreServiceLevel ? 'ServiceLevelError' : null));

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitauthorizedorder');
        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('getCapturePaymentService', 'getEditObject'))
            ->getMock();
        $sut->expects($this->once())
            ->method('getCapturePaymentService')
            ->will($this->returnValue($mockCaptureServie));
        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));
        $sut->capture();
        return $sut;
    }

    /**
     * @param int $iShippingNumber Service response: integer number of captured shipping. Do not confuse with arbitrary
     *     tracking id,./runte
     *
     * @param $bErrorsAreServiceLevel
     *
     * @return OrderAfterpay
     */
    protected function getCaptureshippingMockedSut($iShippingNumber, $bErrorsAreServiceLevel = true)
    {
        $mockCaptureShippingResponseEntity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity::class);
        $mockCaptureShippingResponseEntity->setShippingNumber($iShippingNumber);
        $mockCaptureShippingResponseEntity->setErrors(['ResponseLevelError']);

        $mockCaptureServie = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CaptureShippingService::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->setMethods(array('captureShipping', 'getErrorMessages'))
            ->getMock();
        $mockCaptureServie->expects($this->once())
            ->method('captureShipping')
            ->will($this->returnValue($mockCaptureShippingResponseEntity));

        // If there is a captured amount getErrorMessages() is not to be called
        $errorAccesses = is_numeric($iShippingNumber) ? $this->never() : $this->once();
        $mockCaptureServie->expects($errorAccesses)
            ->method('getErrorMessages')
            ->will($this->returnValue($bErrorsAreServiceLevel ? 'ServiceLevelError' : null));

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitauthorizedorder');
        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('getCaptureShippingService', 'getEditObject'))
            ->getMock();
        $sut->expects($this->once())
            ->method('getCaptureShippingService')
            ->will($this->returnValue($mockCaptureServie));
        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));
        $sut->captureshipping('lorem', 'ipsum');
        return $sut;
    }

    /**
     * @param int $iShippingNumber Service response: integer number of captured shipping. Do not confuse with arbitrary
     *     tracking id,./runte
     *
     * @return OrderAfterpay
     */
    protected function getRefundMockedSut($aRefundNumbers = [800012345])
    {
        $mockRefundResponseEntity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\RefundResponseEntity::class);
        $mockRefundResponseEntity->setRefundNumbers($aRefundNumbers);

        $mockCaptureServie = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\RefundService::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->setMethods(array('refund', 'getErrorMessages'))
            ->getMock();
        $mockCaptureServie->expects($this->once())
            ->method('refund')
            ->will($this->returnValue($mockRefundResponseEntity));

        // If there is a captured amount getErrorMessages() is not to be called
        $errorAccesses = $aRefundNumbers ? $this->never() : $this->once();
        $mockCaptureServie->expects($errorAccesses)
            ->method('getErrorMessages')
            ->will($this->returnValue('LoremIpsum'));

        $oxOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var OrderAfterpay $sut
         */
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Controller\Admin\OrderAfterpay::class)
            ->setMethods(array('getRefundService', 'getEditObject'))
            ->getMock();
        $sut->expects($this->once())
            ->method('getRefundService')
            ->will($this->returnValue($mockCaptureServie));
        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));
        $sut->refund(['lorem', 'ipsum']);
        return $sut;
    }
}
