<?php

/**
 *
 */


namespace Arvato\AfterpayModule\Tests\Unit\Controller;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class OrderTest: Tests for OrderController.
 */
class OrderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testrendernoafterpayinstallment()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'definitlynotafterpay');

        $sut = $this->getSUT_NoInstallment($oxSession);
        $render = $sut->render();
        $this->assertEquals('parent_render_called.tpl', $render);
    }

    public function testrenderafterpayinstallment()
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'afterpayinstallment');

        $sut = $this->getSUT_Installment($oxSession);
        $render = $sut->render();
        $this->assertEquals('parent_render_called.tpl', $render);
    }

    public function testgetAvailableInstallmentPlansSessionLost()
    {
        $sut = $this->getSut_MockedInstallment(0, false);
        $this->assertFalse($sut->getAvailableInstallmentPlans());
    }

    public function testgetAvailableInstallmentPlansNoPlans()
    {
        $sut = $this->getSut_MockedInstallment(123, false);
        $this->assertNull($sut->getAvailableInstallmentPlans());
    }

    public function testgetAvailableInstallmentPlansFoundPlans()
    {
        $sut = $this->getSut_MockedInstallment(123, true);
        $sutReturn = $sut->getAvailableInstallmentPlans();
        $this->assertEquals('{"99":{"installmentProfileNumber":99}}', json_encode($sutReturn));
    }

    public function testupdateSelectedInstallmentPlanProfileIdInSessionIdSet()
    {
        $sut = $this->getSUT_InstallmentProfileId(12);
        $sutReturn = $sut->updateSelectedInstallmentPlanProfileIdInSession(true);
        $this->assertEquals(12, $sutReturn);
    }

    public function testupdateSelectedInstallmentPlanProfileIdInSessionIdNotSet()
    {
        $sut = $this->getSUT_InstallmentProfileId(0);
        $sutReturn = $sut->updateSelectedInstallmentPlanProfileIdInSession(true);
        $this->assertEquals(1, $sutReturn);
    }

    public function testgetNextStepOnNoError()
    {
        $class = new \ReflectionClass(\OxidEsales\Eshop\Application\Controller\OrderController::class);
        $method = $class->getMethod('_getNextStep');
        $method->setAccessible(true);
        $sutReturn = $method->invokeArgs(oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class), [1]);
        $this->assertEquals('thankyou', $sutReturn);
    }

    public function testgetNextStepOnError()
    {
        $class = new \ReflectionClass(\OxidEsales\Eshop\Application\Controller\OrderController::class);
        $method = $class->getMethod('_getNextStep');
        $method->setAccessible(true);
        $sutReturn = $method->invokeArgs(
            oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class),
            [oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class)->getOrderStateCheckAddressConstant()]
        );
        $this->assertEquals('user?wecorrectedyouraddress=1', $sutReturn);
    }

    public function testredirectToPaymentIfNoInstallmentPlanAvailableAlthoughSelectedNoRedirectNotInstallmentSelected()
    {

        $oxSession = Registry::getSession();
        $oxSession->setVariable('paymentid', 'foobar');//''afterpayinstallment');

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('redirectToPayment', 'getSession'))
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

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('redirectToPayment', 'getSession'))
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

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('redirectToPayment', 'getSession'))
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
     * @return OrderController
     */
    protected function getSUTNoInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('parentRender', 'getSession'))
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
     * @return OrderController
     */
    protected function getSUTInstallmentProfileId($installmentProfileId)
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('dynvalue', []);

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('getSession', 'getRequestParameter'))
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
     * @return OrderController
     */
    protected function getSUTInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods([
                'parentRender',
                'getSession',
                'updateSelectedInstallmentPlanProfileIdInSession',
                'getAvailableInstallmentPlans'
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

        $installmentPlan = new \stdClass();
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
     * @return OrderController
     */
    protected function getSutMockedInstallment($fBasketPrice, $foundInstallmentPlans)
    {

        $oxPrice = $this->getMockBuilder('stdClass')->setMethods(['getBruttoPrice'])->getMock();
        $oxPrice->method('getBruttoPrice')->will($this->returnValue($fBasketPrice));

        $oxBasket = $this->getMockBuilder('stdClass')->setMethods(['getPrice'])->getMock();
        $oxBasket->method('getPrice')->will($this->returnValue($oxPrice));

        $oxSession = $this->getMockBuilder('stdClass')->setMethods(['getBasket'])->getMock();
        $oxSession->method('getBasket')->will($this->returnValue($oxBasket));

        if ($foundInstallmentPlans) {
            $installmentPlan = new \stdClass();
            $installmentPlan->effectiveAnnualPercentageRate = 'deleteme';
            $installmentPlan->installmentProfileNumber = 99;
            $availableInstallmentPlans = [$installmentPlan];
        }

        $objAvailableInstallmentPlans = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $objAvailableInstallmentPlans->method('getAvailableInstallmentPlans')->will($this->returnValue($availableInstallmentPlans));

        $availableInstallmentPlansService = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $availableInstallmentPlansService->method('getAvailableInstallmentPlans')->will($this->returnValue($objAvailableInstallmentPlans));

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('getAvailableInstallmentPlansService', 'getSession'))
            ->getMock();
        $sut->method('getAvailableInstallmentPlansService')
            ->will($this->returnValue($availableInstallmentPlansService));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));
        return $sut;
    }
}
