<?php /** @noinspection Annotator */

namespace Arvato\AfterpayModule\Tests\Unit\Controller;

use Arvato\AfterpayModule\Application\Controller\PaymentController as ArvatoPaymentController;
use Arvato\AfterpayModule\Application\Model\Article;
use OxidEsales\Eshop\Application\Controller\PaymentController;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

/**
 * Class PaymentController: Tests for PaymentController.
 */
class PaymentTest extends UnitTestCase
{

    public function testrender()
    {
        /** @var PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods(['parentRender'])
                    ->getMock();
        $sut->expects($this->once())
            ->method('parentRender')
            ->will($this->returnValue('###OK###'));
        $render = $sut->render();
        $this->assertEquals('###OK###', $render);
    }

    public function testvalidatePaymentNoAfterPayPayment()
    {
        /** @var PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods([
                        'getRequestOrSessionParameter',
                        'validateDebitNote',
                        'validateAndSaveSelectedInstallmentPforileId',
                        'validateInstallment',
                    ])
                    ->getMock();

        $sut->expects($this->exactly(3))
            ->method('getRequestOrSessionParameter')
            ->withConsecutive([$this->stringContains('paymentid')], [$this->stringContains('dynvalue')], [$this->stringContains('AfterPayTrackingEnabled')])
            ->will($this->onConsecutiveCalls('oxempty', ['lorem' => 'ipsum'], 1));

        $sut->expects($this->never())->method('validateDebitNote');
        $sut->expects($this->never())->method('validateAndSaveSelectedInstallmentPforileId');
        $sut->expects($this->never())->method('validateInstallment');

        $render = $sut->validatePayment();
        $this->assertEquals(0, $render);
    }

    public function testvalidatePaymentDebitNote()
    {
        /** @var PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods([
                        'getRequestOrSessionParameter',
                        'validateDebitNote',
                        'validateAndSaveSelectedInstallmentPforileId',
                        'validateInstallment',
                        'parentValidatePayment',
                    ])
                    ->getMock();

        $sut->expects($this->exactly(3))
            ->method('getRequestOrSessionParameter')
            ->withConsecutive([$this->stringContains('paymentid')], [$this->stringContains('dynvalue')], [$this->stringContains('AfterPayTrackingEnabled')])
            ->will($this->onConsecutiveCalls('afterpaydebitnote', ['lorem' => 'ipsum'], 1));

        $sut->expects($this->once())->method('validateDebitNote')->will($this->returnValue(0));
        $sut->expects($this->never())->method('validateAndSaveSelectedInstallmentPforileId');
        $sut->expects($this->never())->method('validateInstallment');
        $sut->expects($this->once())->method('parentValidatePayment')->will($this->returnValue('order'));

        $render = $sut->validatePayment();
        $this->assertEquals('order', $render);
    }

    public function testvalidatePaymentInstallment()
    {
        /** @var PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods([
                        'getRequestOrSessionParameter',
                        'validateDebitNote',
                        'validateAndSaveSelectedInstallmentPforileId',
                        'validateInstallment',
                        'parentValidatePayment',
                    ])
                    ->getMock();

        $sut->expects($this->exactly(3))
            ->method('getRequestOrSessionParameter')
            ->withConsecutive([$this->stringContains('paymentid')], [$this->stringContains('dynvalue')], [$this->stringContains('AfterPayTrackingEnabled')])
            ->will($this->onConsecutiveCalls('afterpayinstallment', ['lorem' => 'ipsum'], 1));

        $sut->expects($this->once())->method('validateInstallment')->will($this->returnValue(0));
        $sut->expects($this->once())->method('validateAndSaveSelectedInstallmentPforileId');
        $sut->expects($this->never())->method('validateDebitNote');
        $sut->expects($this->once())->method('parentValidatePayment')->will($this->returnValue('order'));

        $render = $sut->validatePayment();
        $this->assertEquals('order', $render);
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

    public function testvalidateDebitNote()
    {
        $dynValue = [
            'apdebitbankaccount' => 1,
            'apdebitbankcode'    => false,
        ];

        $sut = oxNew(PaymentController::class);
        $this->assertEquals(0, $sut->validateDebitNote($dynValue));
    }

    public function testvalidateInstallment()
    {
        $dynValue = [
            'apinstallmentbankaccount' => 1,
            'apinstallmentbankcode'    => false,
        ];

        $sut = oxNew(PaymentController::class);
        $this->assertEquals(1, $sut->validateInstallment($dynValue));
    }

    public function testvalidateAndSaveSelectedInstallmentPforileIdNoError()
    {
        $sut = oxNew(PaymentController::class);
        $sutReturn = $sut->validateAndSaveSelectedInstallmentPforileId(['afterpayInstallmentProfileId' => 1]);
        $this->assertEquals(0, $sutReturn);
    }

    public function testvalidateAndSaveSelectedInstallmentPforileIdUnselectedProfileIdError()
    {
        $sut = oxNew(PaymentController::class);
        $sutReturn = $sut->validateAndSaveSelectedInstallmentPforileId(['afterpayInstallmentProfileId' => null]);
        $this->assertEquals(-13337, $sutReturn);
    }

    public function testassignRequiredDynValue()
    {
        /** @var PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods(['getUser'])
                    ->getMock();

        $sut->method('getUser')
            ->will($this->returnValue(true));

        $this->assertTrue((bool) $sut->assignRequiredDynValue());
    }

    public function testallowAfterpayPaymentNotAllowed()
    {
        $sut = $this->getSUTallowAfterpay();

        Registry::getConfig()->setConfigParam('arvatoAfterpayExcludedArticleNr', 'test');
        $this->assertFalse($sut->allowAfterpayPayment());
    }

    public function testallowAfterpayPaymentSuccess()
    {
        $sut = $this->getSUTallowAfterpay();

        Registry::getConfig()->setConfigParam('arvatoAfterpayExcludedArticleNr', '1234');
        $this->assertTrue($sut->allowAfterpayPayment());
    }

    /////////////////////////////////////////////////////////////////////
    /// END OF TESTS - STARTING HELPERS

    /**
     * Will give SUT with mocked parent::render() and mocked get_session()
     *
     * @param null $oxSession
     *
     * @return PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUTNoInstallment($oxSession = null)
    {
        /** @var PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentController::class)
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
     * @return PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUTInstallmentProfileId($installmentProfileId)
    {
        $oxSession = Registry::getSession();
        $oxSession->setVariable('dynvalue', []);

        $sut = $this->getMockBuilder(PaymentController::class)
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
     * @return PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUTInstallment($oxSession = null)
    {
        $sut = $this->getMockBuilder(PaymentController::class)
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
     * @return PaymentController|ArvatoPaymentController|PHPUnit_Framework_MockObject_MockObject
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
        $objAvailableInstallmentPlans->method('getAvailableInstallmentPlans')->will($this->returnValue($availableInstallmentPlans ?: null));

        $availableInstallmentPlansService = $this->getMockBuilder('stdClass')->setMethods(['getAvailableInstallmentPlans'])->getMock();
        $availableInstallmentPlansService->method('getAvailableInstallmentPlans')->will($this->returnValue($objAvailableInstallmentPlans));

        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods(['getAvailableInstallmentPlansService', 'getSession'])
                    ->getMock();
        $sut->method('getAvailableInstallmentPlansService')
            ->will($this->returnValue($availableInstallmentPlansService));
        $sut->expects($this->atLeastOnce())
            ->method('getSession')
            ->will($this->returnValue($oxSession));

        return $sut;
    }

    /**
     * Will give SUT with mocked getSession (containing basket with article)
     *
     * @return PaymentController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSUTallowAfterpay()
    {
        $basketArticle = $this->getMockBuilder(Article::class)
                              ->setMethods(['load'])
                              ->getMock();
        $basketArticle->oxarticles__oxartnum = new Field('test');

        $basket = $this->getMockBuilder(Basket::class)
                       ->setMethods(['getBasketArticles'])
                       ->getMock();
        $basket->method('getBasketArticles')
               ->will($this->returnValue([$basketArticle]));

        $session = $this->getMockBuilder(Session::class)
                        ->disableOriginalConstructor()
                        ->disableOriginalClone()
                        ->setMethods(['getBasket'])
                        ->getMock();
        $session->method('getBasket')
                ->will($this->returnValue($basket));

        $sut = $this->getMockBuilder(PaymentController::class)
                    ->setMethods(['getSession'])
                    ->getMock();
        $sut->method('getSession')
            ->will($this->returnValue($session));

        return $sut;
    }
}
