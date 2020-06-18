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
 * Class PaymentController: Tests for PaymentController.
 */
class PaymentTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testrender()
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
            ->setMethods(array('parentRender'))
            ->getMock();
        $sut->expects($this->once())
            ->method('parentRender')
            ->will($this->returnValue('###OK###'));
        $render = $sut->render();
        $this->assertEquals('###OK###', $render);
    }

    public function testvalidatePaymentNoAfterPayPayment()
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
            ->setMethods([
                'getRequestOrSessionParameter',
                'validateDebitNote',
                'validateAndSaveSelectedInstallmentPforileId',
                'validateInstallment'
            ])
            ->getMock();

        $sut->expects($this->exactly(2))
            ->method('getRequestOrSessionParameter')
            ->withConsecutive([$this->stringContains('paymentid')], [$this->stringContains('dynvalue')])
            ->will($this->onConsecutiveCalls('SomethingElse', ['lorem' => 'ipsum']));

        $sut->expects($this->never())->method('validateDebitNote');
        $sut->expects($this->never())->method('validateAndSaveSelectedInstallmentPforileId');
        $sut->expects($this->never())->method('validateInstallment');

        $render = $sut->validatePayment();
        $this->assertEquals(0, $render);
    }

    public function testvalidatePaymentDebitNote()
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
            ->setMethods([
                'getRequestOrSessionParameter',
                'validateDebitNote',
                'validateAndSaveSelectedInstallmentPforileId',
                'validateInstallment',
                'parentValidatePayment'
            ])
            ->getMock();

        $sut->expects($this->exactly(2))
            ->method('getRequestOrSessionParameter')
            ->withConsecutive([$this->stringContains('paymentid')], [$this->stringContains('dynvalue')])
            ->will($this->onConsecutiveCalls('afterpaydebitnote', ['lorem' => 'ipsum']));

        $sut->expects($this->once())->method('validateDebitNote')->will($this->returnValue(0));
        $sut->expects($this->never())->method('validateAndSaveSelectedInstallmentPforileId');
        $sut->expects($this->never())->method('validateInstallment');
        $sut->expects($this->once())->method('parentValidatePayment')->will($this->returnValue('order'));

        $render = $sut->validatePayment();
        $this->assertEquals('order', $render);
    }

    public function testvalidatePaymentInstallment()
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
            ->setMethods([
                'getRequestOrSessionParameter',
                'validateDebitNote',
                'validateAndSaveSelectedInstallmentPforileId',
                'validateInstallment',
                'parentValidatePayment'
            ])
            ->getMock();

        $sut->expects($this->exactly(2))
            ->method('getRequestOrSessionParameter')
            ->withConsecutive([$this->stringContains('paymentid')], [$this->stringContains('dynvalue')])
            ->will($this->onConsecutiveCalls('afterpayinstallment', ['lorem' => 'ipsum']));

        $sut->expects($this->once())->method('validateInstallment')->will($this->returnValue(0));
        $sut->expects($this->once())->method('validateAndSaveSelectedInstallmentPforileId');
        $sut->expects($this->never())->method('validateDebitNote');
        $sut->expects($this->once())->method('parentValidatePayment')->will($this->returnValue('order'));

        $render = $sut->validatePayment();
        $this->assertEquals('order', $render);
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

    public function testvalidateDebitNote()
    {

        $aDynvalue = [
            'apdebitbankaccount' => 1,
            'apdebitbankcode'    => false,
        ];

        $sut = oxNew(\OxidEsales\Eshop\Application\Controller\PaymentController::class);
        $this->assertEquals(1, $sut->validateDebitNote($aDynvalue));
    }

    public function testvalidateInstallment()
    {
        $aDynvalue = [
            'apinstallmentbankaccount' => 1,
            'apinstallmentbankcode'    => false,
        ];

        $sut = oxNew(\OxidEsales\Eshop\Application\Controller\PaymentController::class);
        $this->assertEquals(1, $sut->validateInstallment($aDynvalue));
    }

    public function testvalidateAndSaveSelectedInstallmentPforileIdNoError()
    {
        $sut = oxNew(\OxidEsales\Eshop\Application\Controller\PaymentController::class);
        $sutReturn = $sut->validateAndSaveSelectedInstallmentPforileId(['afterpayInstallmentProfileId' => 1]);
        $this->assertEquals(0, $sutReturn);
    }

    public function testvalidateAndSaveSelectedInstallmentPforileIdUnselectedProfileIdError()
    {
        $sut = oxNew(\OxidEsales\Eshop\Application\Controller\PaymentController::class);
        $sutReturn = $sut->validateAndSaveSelectedInstallmentPforileId(['afterpayInstallmentProfileId' => null]);
        $this->assertEquals(-13337, $sutReturn);
    }

    public function testassignRequiredDynValue()
    {

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
            ->setMethods(array('getUser'))
            ->getMock();

        $sut->method('getUser')
            ->will($this->returnValue(true));

        $this->assertTrue((bool)$sut->assignRequiredDynValue());
    }

    /////////////////////////////////////////////////////////////////////
    /// END OF TESTS - STARTING HELPERS

    /**
     * Will give SUT with mocked parent::render() and mocked get_session()
     *
     * @param null $oxSession
     *
     * @return PaymentController
     */
    protected function getSUTNoInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
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
     * @return PaymentController
     */
    protected function getSUTInstallmentProfileId($iInstallmentProfileId)
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('dynvalue', []);

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
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
     * @return PaymentController
     */
    protected function getSUTInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
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
     * @return PaymentController
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
        $oAvailableInstallmentPlans->method('getAvailableInstallmentPlans')->will($this->returnValue($aAvailableInstallmentPlans ?: null));

        $availableInstallmentPlansService = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $availableInstallmentPlansService->method('getAvailableInstallmentPlans')->will($this->returnValue($oAvailableInstallmentPlans));

        $sut = $this->getMockBuilder(\OxidEsales\Eshop\Application\Controller\PaymentController::class)
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
