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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Controller;

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
     * @param int $iInstallmentProfileId
     *
     * @return OrderController
     */
    protected function getSUTInstallmentProfileId($iInstallmentProfileId)
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('dynvalue', []);

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\OrderController::class)
            ->setMethods(array('getSession', 'getRequestParameter'))
            ->getMock();

        $sut->expects($this->atLeastOnce())
            ->method('getRequestParameter')
            ->will($this->returnValue($iInstallmentProfileId));

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

        $oInstallmentplan = new \stdClass();
        $oInstallmentplan->readMore = '';
        $oInstallmentplan->totalInterestAmount = 1;
        $oInstallmentplan->totalAmount = 2;
        $oInstallmentplan->effectiveInterestRate = 3;

        $sut->expects($this->atLeastOnce())
            ->method('getAvailableInstallmentPlans')
            ->will($this->returnValue([1 => $oInstallmentplan]));

        return $sut;
    }

    /**
     * @param $fBasketPrice
     * @param $bFoundInstallmentPlans
     *
     * @return OrderController
     */
    protected function getSutMockedInstallment($fBasketPrice, $bFoundInstallmentPlans)
    {

        $oxPrice = $this->getMockBuilder('stdClass')->setMethods(['getBruttoPrice'])->getMock();
        $oxPrice->method('getBruttoPrice')->will($this->returnValue($fBasketPrice));

        $oxBasket = $this->getMockBuilder('stdClass')->setMethods(['getPrice'])->getMock();
        $oxBasket->method('getPrice')->will($this->returnValue($oxPrice));

        $oxSession = $this->getMockBuilder('stdClass')->setMethods(['getBasket'])->getMock();
        $oxSession->method('getBasket')->will($this->returnValue($oxBasket));

        if ($bFoundInstallmentPlans) {
            $installmentPlan = new \stdClass();
            $installmentPlan->effectiveAnnualPercentageRate = 'deleteme';
            $installmentPlan->installmentProfileNumber = 99;
            $aAvailableInstallmentPlans = [$installmentPlan];
        }

        $oAvailableInstallmentPlans = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $oAvailableInstallmentPlans->method('getAvailableInstallmentPlans')->will($this->returnValue($aAvailableInstallmentPlans));

        $availableInstallmentPlansService = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $availableInstallmentPlansService->method('getAvailableInstallmentPlans')->will($this->returnValue($oAvailableInstallmentPlans));

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
