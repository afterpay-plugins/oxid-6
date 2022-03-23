<?php /** @noinspection Annotator */

namespace Arvato\AfterpayModule\Tests\Unit\Controller;

use Arvato\AfterpayModule\Application\Controller\OrderController as ArvatoOrderController;
use OxidEsales\Eshop\Application\Controller\OrderController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;
use ReflectionException;
use stdClass;

/**
 * Class OrderTest: Tests for OrderController.
 */
class OrderTest extends UnitTestCase
{

    public function testrendernoafterpayinstallment()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'definitelynotafterpay');

        $sut = $this->getSUTNoInstallment($oxSession);
        $render = $sut->render();
        $this->assertEquals('parent_render_called.tpl', $render);
    }

    public function testrenderafterpayinstallment()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'afterpayinstallment');

        $sut = $this->getSUTInstallment($oxSession);
        $render = $sut->render();
        $this->assertEquals('parent_render_called.tpl', $render);
    }

    public function testgetAvailableInstallmentPlansSessionLost()
    {
        $sut = $this->getSutMockedInstallment(0, false);
        $this->assertFalse($sut->getAvailableInstallmentPlans());
    }

    public function testgetAvailableInstallmentPlansNoPlans()
    {
        $sut = $this->getSutMockedInstallment(123, false);
        $this->assertNull($sut->getAvailableInstallmentPlans());
    }

    public function testgetAvailableInstallmentPlansFoundPlans()
    {
        $sut = $this->getSutMockedInstallment(123, true);
        $sutReturn = $sut->getAvailableInstallmentPlans();
        $this->assertEquals('{"99":{"installmentProfileNumber":99}}', json_encode($sutReturn));
    }

    public function testupdateSelectedInstallmentPlanProfileIdInSessionIdSet()
    {
        $sut = $this->getSUTInstallmentProfileId(12);
        $sutReturn = $sut->updateSelectedInstallmentPlanProfileIdInSession(true);
        $this->assertEquals(12, $sutReturn);
    }

    public function testupdateSelectedInstallmentPlanProfileIdInSessionIdNotSet()
    {
        $sut = $this->getSUTInstallmentProfileId(0);
        $sutReturn = $sut->updateSelectedInstallmentPlanProfileIdInSession(true);
        $this->assertEquals(1, $sutReturn);
    }

    /**
     * @throws ReflectionException
     */
    public function testgetNextStepOnNoError()
    {
        $class = new ReflectionClass(OrderController::class);
        $method = $class->getMethod('_getNextStep');
        $method->setAccessible(true);
        $sutReturn = $method->invokeArgs(oxNew(OrderController::class), [1]);
        $this->assertEquals('thankyou', $sutReturn);
    }

    /**
     * @throws ReflectionException
     */
    public function testgetNextStepOnCfmError()
    {
        $errorMessage = 'customer facing error message';

        $class = new ReflectionClass(ArvatoOrderController::class);
        $method = $class->getMethod('_getNextStep');
        $method->setAccessible(true);
        $sutReturn = $method->invokeArgs(
            oxNew(OrderController::class),
            [$errorMessage] // Needs to be longer than 10 characters
        );
        $this->assertEquals('user?cfm=1', $sutReturn);

        $oxSession = Registry::getSession();
        $this->assertEquals($errorMessage, $oxSession->getVariable('arvatoAfterpayCustomerFacingMessage'));
    }

    /**
     * @throws ReflectionException
     */
    public function testgetNextStepOnAddressCorrection()
    {
        $class = new ReflectionClass(ArvatoOrderController::class);
        $method = $class->getMethod('_getNextStep');
        $method->setAccessible(true);
        $sutReturn = $method->invokeArgs(
            oxNew(OrderController::class),
            [oxNew(OrderController::class)->getOrderStateCheckAddressConstant()]
        );
        $this->assertEquals('user?wecorrectedyouraddress=1', $sutReturn, 'Module might not be active');
    }

    public function testredirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelectedNoRedirectNotInstallmentSelected()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'foobar');//''afterpayinstallment');

        /** @var OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods(['redirectToPayment', 'getSession'])
                    ->getMock();
        $sut->expects($this->never())
            ->method('redirectToPayment')
            ->will($this->returnValue(null));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        //Assertion is the method call expectation

        $sut->redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected([1, 2]);
    }

    public function testredirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelectedNoRedirectPlansAvailable()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'afterpayinstallment');

        /** @var OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods(['redirectToPayment', 'getSession'])
                    ->getMock();
        $sut->expects($this->never())
            ->method('redirectToPayment')
            ->will($this->returnValue(null));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        //Assertion is the method call expectation

        $sut->redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected([1, 2]);
    }

    public function testredirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelectedRedirect()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'afterpayinstallment');

        /** @var OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods(['redirectToPayment', 'getSession'])
                    ->getMock();
        $sut->expects($this->once())
            ->method('redirectToPayment')
            ->will($this->returnValue(null));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        //Assertion is the method call expectation

        $sut->redirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelected(null);
    }


    /////////////////////////////////////////////////////////////////////
    /// END OF TESTS - STARTING HELPERS

    /**
     * Will give SUT with mocked parent::render() and mocked get_session()
     *
     * @param null $oxSession
     *
     * @return OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUTNoInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods(['parentRender', 'getSession'])
                    ->getMock();
        $sut->method('parentRender')
            ->will($this->returnValue('parent_render_called.tpl'));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        return $sut;
    }

    /**
     * Will give SUT with mocked parent::render() and mocked get_session()
     *
     * @param int $installmentProfileId
     *
     * @return OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUTInstallmentProfileId($installmentProfileId)
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('dynvalue', []);

        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods(['getSession', 'getRequestParameter'])
                    ->getMock();

        $sut->expects($this->atLeastOnce())
            ->method('getRequestParameter')
            ->will($this->returnValue($installmentProfileId));

        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        return $sut;
    }

    /**
     * Will give SUT with mocked parent::render() and mocked get_session()
     *
     * @param null $oxSession
     *
     * @return OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUTInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods([
                        'parentRender',
                        'getSession',
                        'updateSelectedInstallmentPlanProfileIdInSession',
                        'getAvailableInstallmentPlans',
                    ])
                    ->getMock();
        $sut->method('parentRender')
            ->will($this->returnValue('parent_render_called.tpl'));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));
        $sut->expects($this->atLeastOnce())
            ->method('updateSelectedInstallmentPlanProfileIdInSession')
            ->will($this->returnValue(1));

        $installmentPlan = new stdClass();
        $installmentPlan->readMore = '';
        $installmentPlan->totalInterestAmount = 1;
        $installmentPlan->totalAmount = 2;
        $installmentPlan->effectiveInterestRate = 3;

        $sut->expects($this->atLeastOnce())
            ->method('getAvailableInstallmentPlans')
            ->will($this->returnValue([1 => $installmentPlan]));

        return $sut;
    }

    /**
     * @param $fBasketPrice
     * @param $foundInstallmentPlans
     *
     * @return OrderController|ArvatoOrderController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSutMockedInstallment($fBasketPrice, $foundInstallmentPlans)
    {
        $oxPrice = $this->getMockBuilder('stdClass')->setMethods(['getBruttoPrice'])->getMock();
        $oxPrice->method('getBruttoPrice')->will($this->returnValue($fBasketPrice));

        $oxBasket = $this->getMockBuilder('stdClass')->setMethods(['getPrice'])->getMock();
        $oxBasket->method('getPrice')->will($this->returnValue($oxPrice));

        $oxSession = $this->getMockBuilder('stdClass')->setMethods(['getBasket'])->getMock();
        $oxSession->method('getBasket')->will($this->returnValue($oxBasket));

        $availableInstallmentPlans = null;
        if ($foundInstallmentPlans) {
            $installmentPlan = new stdClass();
            $installmentPlan->effectiveAnnualPercentageRate = 'deleteme';
            $installmentPlan->installmentProfileNumber = 99;
            $availableInstallmentPlans = [$installmentPlan];
        }

        $objAvailableInstallmentPlans = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $objAvailableInstallmentPlans->method('getAvailableInstallmentPlans')->will($this->returnValue($availableInstallmentPlans));

        $availableInstallmentPlansService = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $availableInstallmentPlansService->method('getAvailableInstallmentPlans')->will($this->returnValue($objAvailableInstallmentPlans));

        $sut = $this->getMockBuilder(OrderController::class)
                    ->setMethods(['getAvailableInstallmentPlansService', 'getSession'])
                    ->getMock();
        $sut->method('getAvailableInstallmentPlansService')
            ->will($this->returnValue($availableInstallmentPlansService));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        return $sut;
    }
}
