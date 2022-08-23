<?php /** @noinspection Annotator */

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Controller\Admin;

use Arvato\AfterpayModule\Application\Controller\Admin\OrderAfterpay;
use Arvato\AfterpayModule\Application\Model\Entity\CaptureResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\RefundResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\VoidResponseEntity;
use Arvato\AfterpayModule\Core\CaptureService;
use Arvato\AfterpayModule\Core\CaptureShippingService;
use Arvato\AfterpayModule\Core\RefundService;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

/**
 * Class OrderAfterpayTest: Tests for OrderAfterpay.
 */
class OrderAfterpayTest extends UnitTestCase
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

        $viewData = $sut->getViewData();

        $this->assertTrue(isset($viewData['oOrder']), 'isset oOrder');

        if (isset($viewData['oOrder'])) {
            $this->assertEquals(
                $oxID,
                $viewData['oOrder']->getId()
            );
        }

        $this->assertTrue(isset($viewData['oAfterpayOrder']));
        if (isset($viewData['oAfterpayOrder'])) {
            $this->assertEquals(
                $oxID,
                $viewData['oAfterpayOrder']->getId()
            );
        }

        $this->assertFalse(isset($viewData['sMessage']));
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

        $viewData = $sut->getViewData();

        $this->assertFalse(isset($viewData['oOrder']), 'isset aOrder');
        $this->assertFalse(isset($viewData['oAfterpayOrder']), 'isset aAfterpayOrder');
        $this->assertTrue(isset($viewData['sMessage']), 'isset sMessage');
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

        $viewData = $sut->getViewData();

        $this->assertFalse(isset($viewData['oOrder']));
        $this->assertFalse(isset($viewData['oAfterpayOrder']));
        $this->assertTrue(isset($viewData['sMessage']));
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
        $viewData = $sut->getViewData();
        $this->assertFalse(isset($viewData['aErrorMessages']));
        $this->assertTrue(isset($viewData['oCaptureSuccess']));
    }

    public function testCaptureDidNotCaptureServiceLevelError()
    {
        $sut = $this->getCaptureMockedSut(0, true);
        $viewData = $sut->getViewData();
        $this->assertEquals('ServiceLevelError', $viewData['aErrorMessages']);
        $this->assertFalse(isset($viewData['oCaptureSuccess']));
    }

    public function testCaptureDidNotCaptureResponseLevelError()
    {
        $sut = $this->getCaptureMockedSut(0, false);
        $viewData = $sut->getViewData();
        $this->assertEquals(['ResponseLevelError'], $viewData['aErrorMessages']);
        $this->assertFalse(isset($viewData['oCaptureSuccess']));
    }

    public function testCaptureshippingSuccessfull()
    {
        $sut = $this->getCaptureshippingMockedSut(123);
        $viewData = $sut->getViewData();
        $this->assertFalse(isset($viewData['aErrorMessages']), 'error messages set');
        $this->assertTrue(isset($viewData['oCaptureShippingSuccess']), 'capture shipping success set');
    }

    public function testCaptureshippingUnsuccessfullServiceLevelError()
    {
        $sut = $this->getCaptureshippingMockedSut(null, true);
        $viewData = $sut->getViewData();
        $this->assertEquals('ServiceLevelError', $viewData['aErrorMessages'], 'error messages set');
        $this->assertFalse(isset($viewData['oCaptureShippingSuccess']), 'capture shipping success set');
    }

    public function testCaptureshippingUnsuccessfullResponseLevelError()
    {
        $sut = $this->getCaptureshippingMockedSut(null, false);
        $viewData = $sut->getViewData();
        $this->assertEquals(['ResponseLevelError'], $viewData['aErrorMessages'], 'error messages set');
        $this->assertFalse(isset($viewData['oCaptureShippingSuccess']), 'capture shipping success set');
    }

    /**
     * @throws StandardException
     */
    public function testrefundSuccessfull()
    {
        $sut = $this->getRefundMockedSut([800012345]);
        $viewData = $sut->getViewData();
        $this->assertFalse(isset($viewData['aErrorMessages']), 'error messages set');
        $this->assertTrue(isset($viewData['oRefundSuccess']), 'refund success set');
    }

    /**
     * @throws StandardException
     */
    public function testrefundUnsuccessfull()
    {
        $sut = $this->getRefundMockedSut(null);
        $viewData = $sut->getViewData();
        $this->assertTrue(isset($viewData['aErrorMessages']), 'error messages set');
        $this->assertFalse(isset($viewData['oRefundSuccess']), 'refund success set');
    }

    /**
     * @throws StandardException
     */
    public function testrefundException()
    {
        $this->expectException(StandardException::class);

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['getEditObject'])
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

        $orderarticle_vat19 = new stdClass();
        $orderarticle_vat19->oxorderarticles__oxvat = new Field(19);

        $orderArticles = [$orderarticle_vat19, $orderarticle_vat19];
        $this->assertEquals(
            [19],
            $this->getSUT('lorem')->getRefundVatPercentages($orderArticles)
        );
    }

    public function testsmartyAssignOrderDetails()
    {

        $article = new stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article,
        ];

        $response = $this->getMockBuilder(OrderDetailsResponseEntity::class)
                         ->disableOriginalConstructor()
                         ->setMethods(['getOrderItems'])
                         ->getMock();
        $response->method('getOrderItems')
                 ->will($this->returnValue($items));

        $service = $this->getMockBuilder(CaptureService ::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getOrderDetails'])
                        ->getMock();
        $service->method('getOrderDetails')
                ->will($this->returnValue($response));

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');

        /** @var $sut OrderAfterpay|PHPUnit_Framework_MockObject_MockObject */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['getOrderDetailsService', 'getEditObject'])
                    ->getMock();
        $sut->method('getOrderDetailsService')
            ->will($this->returnValue($service));
        $sut->method('getEditObject')
            ->will($this->returnValue($oxOrder));

        $sut->smartyAssignOrderDetails();

        $viewData = $sut->getViewData();

        $this->assertEquals(
            $items,
            $viewData['aArvatoAllOrderItems']
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

    /**
     * @return OrderAfterpay|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getOrderItemActionSUT()
    {

        $service = $this->getMockBuilder(CaptureService ::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getOrderDetails'])
                        ->getMock();
        $service->method('getOrderDetails')
                ->will($this->returnValue(null));

        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods([
                        'getFromRequest',
                        'getOrderDetailsService',
                        'orderitemactionCapture',
                        'orderitemactionRefund',
                        'orderitemactionVoid',
                    ])
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

        $article = new stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article,
        ];

        $response = $this->getMockBuilder(OrderDetailsResponseEntity::class)
                         ->disableOriginalConstructor()
                         ->setMethods(['getOrderItems'])
                         ->getMock();
        $response->method('getOrderItems')
                 ->will($this->returnValue($items));
        /** @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['capture'])
                    ->getMock();
        $sut->expects($this->once())
            ->method('capture')
            ->with($this->equalTo($items))// This is the assertion
            ->will($this->returnValue(null));

        $sut->orderitemactionCapture($response, $items);
    }

    public function testorderitemactionRefund()
    {

        $article = new stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article,
        ];

        $response = $this->getMockBuilder(OrderDetailsResponseEntity::class)
                         ->disableOriginalConstructor()
                         ->setMethods(['getOrderItems'])
                         ->getMock();
        $response->method('getOrderItems')
                 ->will($this->returnValue($items));
        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
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

        $article = new stdClass();
        $article->quantity = 3;

        $items = [
            1 => $article,
            2 => $article,
        ];

        $response = $this->getMockBuilder(OrderDetailsResponseEntity::class)
                         ->disableOriginalConstructor()
                         ->setMethods(['getOrderItems'])
                         ->getMock();
        $response->method('getOrderItems')
                 ->will($this->returnValue($items));
        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['void'])
                    ->getMock();

        $sut->expects($this->once())
            ->method('void')
            ->with($this->equalTo($items))// This is the assertion
            ->will($this->returnValue(null));

        $sut->orderitemactionVoid($response, $items);
    }

    public function testvoidSuccess()
    {

        $response = $this->getMockBuilder(VoidResponseEntity::class)
                         ->disableOriginalConstructor()
                         ->disableOriginalClone()
                         ->setMethods(['getTotalAuthorizedAmount'])
                         ->getMock();
        $response->expects($this->once())
                 ->method('getTotalAuthorizedAmount')
                 ->will($this->returnValue(123));

        $mockVoidServie = $this->getMockBuilder(RefundService::class)
                               ->disableOriginalConstructor()
                               ->disableOriginalClone()
                               ->setMethods(['void'])
                               ->getMock();
        $mockVoidServie->expects($this->once())
                       ->method('void')
                       ->will($this->returnValue($response));

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['getEditObject', 'getVoidPaymentService'])
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
     * @return OrderAfterpay|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUT($oxOrderId)
    {
        /** @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['smartyAssignOrderDetails'])
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
     * @param $capturedAmount
     * @param $errorsAreServiceLevel
     *
     * @return OrderAfterpay
     */
    protected function getCaptureMockedSut($capturedAmount, $errorsAreServiceLevel = true)
    {
        $mockCaptureResponseEntity = oxNew(CaptureResponseEntity::class);
        $mockCaptureResponseEntity->setCapturedAmount($capturedAmount);
        $mockCaptureResponseEntity->setErrors(['ResponseLevelError']);

        $mockCaptureServie = $this->getMockBuilder(CaptureService::class)
                                  ->disableOriginalConstructor()
                                  ->disableOriginalClone()
                                  ->setMethods(['capture', 'getErrorMessages'])
                                  ->getMock();
        $mockCaptureServie->expects($this->once())
                          ->method('capture')
                          ->will($this->returnValue($mockCaptureResponseEntity));

        // If there is a captured amount getErrorMessages() is not to be called
        $errorAccesses = $capturedAmount ? $this->never() : $this->once();
        $mockCaptureServie->expects($errorAccesses)
                          ->method('getErrorMessages')
                          ->will($this->returnValue($errorsAreServiceLevel ? 'ServiceLevelError' : null));

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');
        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['getCapturePaymentService', 'getEditObject'])
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
     * @param int $shippingNumber Service response: integer number of captured shipping. Do not confuse with arbitrary
     *                            tracking id,./runte
     *
     * @param     $errorsAreServiceLevel
     *
     * @return OrderAfterpay
     */
    protected function getCaptureshippingMockedSut($shippingNumber, $errorsAreServiceLevel = true)
    {
        $mockCaptureShippingResponseEntity = oxNew(CaptureShippingResponseEntity::class);
        $mockCaptureShippingResponseEntity->setShippingNumber($shippingNumber);
        $mockCaptureShippingResponseEntity->setErrors(['ResponseLevelError']);

        $mockCaptureServie = $this->getMockBuilder(CaptureShippingService::class)
                                  ->disableOriginalConstructor()
                                  ->disableOriginalClone()
                                  ->setMethods(['captureShipping', 'getErrorMessages'])
                                  ->getMock();
        $mockCaptureServie->expects($this->once())
                          ->method('captureShipping')
                          ->will($this->returnValue($mockCaptureShippingResponseEntity));

        // If there is a captured amount getErrorMessages() is not to be called
        $errorAccesses = is_numeric($shippingNumber) ? $this->never() : $this->once();
        $mockCaptureServie->expects($errorAccesses)
                          ->method('getErrorMessages')
                          ->will($this->returnValue($errorsAreServiceLevel ? 'ServiceLevelError' : null));

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');
        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['getCaptureShippingService', 'getEditObject'])
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
     * @param int[] $refundNumbers
     * @throws StandardException
     * @return OrderAfterpay
     */
    protected function getRefundMockedSut($refundNumbers = [800012345])
    {
        $mockRefundResponseEntity = oxNew(RefundResponseEntity::class);
        $mockRefundResponseEntity->setRefundNumbers($refundNumbers);

        $mockCaptureServie = $this->getMockBuilder(RefundService::class)
                                  ->disableOriginalConstructor()
                                  ->disableOriginalClone()
                                  ->setMethods(['refund', 'getErrorMessages'])
                                  ->getMock();
        $mockCaptureServie->expects($this->once())
                          ->method('refund')
                          ->will($this->returnValue($mockRefundResponseEntity));

        // If there is a captured amount getErrorMessages() is not to be called
        $errorAccesses = $refundNumbers ? $this->never() : $this->once();
        $mockCaptureServie->expects($errorAccesses)
                          ->method('getErrorMessages')
                          ->will($this->returnValue('LoremIpsum'));

        $oxOrder = oxNew(Order::class);
        $oxOrder->load('unitauthorizedorder');

        /**
         * @var OrderAfterpay|PHPUnit_Framework_MockObject_MockObject $sut
         */
        $sut = $this->getMockBuilder(OrderAfterpay::class)
                    ->setMethods(['getRefundService', 'getEditObject'])
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
