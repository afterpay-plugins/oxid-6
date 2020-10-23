<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model;

use Arvato\AfterpayModule\Application\Model\Order as ArvatoOrder;
use Arvato\AfterpayModule\Application\Model\PaymentGateway;
use Arvato\AfterpayModule\Core\AuthorizePaymentService;
use Arvato\AfterpayModule\Core\AvailablePaymentMethodsService;
use Arvato\AfterpayModule\Core\ValidateBankAccountService;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\UserPayment;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;
use ReflectionException;
use stdClass;

/**
 * Class PaymentGatewayTest: Tests for PaymentGateway.
 */
class PaymentGatewayTest extends UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method executePayment for non-afterpay-order.
     * Assert that payment get derefered to other payment providers
     * Majority of assertions is in the mocks! See expects-never / expects-once
     */
    public function testExecutePaymentNoAfterpayOrder()
    {
        $isAfterpayOrder = false;
        $success = false;

        $mockOxOrder = $this->getMockedOrder($isAfterpayOrder);
        $sut = $this->getSUT($isAfterpayOrder, $success);

        $sutReturn = $sut->executePayment(123.45, $mockOxOrder);
        $this->assertEquals('DEREFERED', $sutReturn);
    }

    /**
     * Testing method executePayment for non-accepted orders.
     * Majority of assertions is in the mocks! See expects-never / expects-once
     */
    public function testExecutePaymentAfterpayOrderNotAccepted()
    {
        $isAfterpayOrder = true;
        $success = false;

        $mockOxOrder = $this->getMockedOrder($isAfterpayOrder);
        $sut = $this->getSUT($isAfterpayOrder, $success);

        $sutReturn = $sut->executePayment(123.45, $mockOxOrder);
        $this->assertEquals(false, $sutReturn);
    }

    /**
     * Testing method executePayment for non-accepted orders.
     * Majority of assertions is in the mocks! See expects-never / expects-once
     */
    public function testExecutePaymentAfterpayOrderAccepted()
    {
        $isAfterpayOrder = true;
        $success = true;

        $mockOxOrder = $this->getMockedOrder($isAfterpayOrder);
        $sut = $this->getSUT($isAfterpayOrder, $success);

        $sutReturn = $sut->executePayment(123.45, $mockOxOrder);
        $this->assertEquals(true, $sutReturn);
    }

    /**
     * @throws ReflectionException
     */
    public function testgatherIBANandBIC()
    {
        $iban = new stdClass();
        $iban->name = 'apinstallmentbankaccount';
        $iban->value = 111;
        $bic = new stdClass();
        $bic->name = 'apinstallmentbankcode';
        $bic->value = 222;
        $dynValues = [$iban, $bic];

        $class = new ReflectionClass(PaymentGateway::class);
        $method = $class->getMethod('gatherIBANandBIC');
        $method->setAccessible(true);

        $userPayment = oxNew(UserPayment::class);
        $userPayment->setDynValues($dynValues);
        $sut = oxNew(PaymentGateway::class);
        $sut->setPaymentParams($userPayment);
        $sutReturn = $method->invokeArgs($sut, []);

        $this->assertEquals([222, 111], $sutReturn);
    }

    public function testhandleInstallmentOk()
    {

        $mockValidateService = $this->getMockBuilder(ValidateBankAccountService::class)
                                    ->disableOriginalConstructor()
                                    ->setMethods(['isValid'])
                                    ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(ValidateBankAccountService::class)
                                         ->disableOriginalConstructor()
                                         ->setMethods(['isSpecificInstallmentAvailable'])
                                         ->getMock();
        $mockAvailPaymenteService
            ->method('isSpecificInstallmentAvailable')
            ->will($this->returnValue(true));

        /** @var PaymentGateway|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentGateway::class)
                    ->disableOriginalConstructor()
                    ->setMethods([
                        'gatherIBANandBIC',
                        'getValidateBankAccountService',
                        'getAvailablePaymentMethodsService',
                    ])
                    ->getMock();
        $sut->getSession()->setVariable('dynvalue', ['afterpayInstallmentProfileId' => 1]);

        $sut->method('gatherIBANandBIC')->will($this->returnValue([111, 222]));
        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));

        $this->assertEquals(12345, $sut->handleInstallment(oxNew(Order::class)));
    }


    public function testhandleInstallmentNotavailable()
    {

        $mockValidateService = $this->getMockBuilder(ValidateBankAccountService::class)
                                    ->disableOriginalConstructor()
                                    ->setMethods(['isValid'])
                                    ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(AvailablePaymentMethodsService::class)
                                         ->disableOriginalConstructor()
                                         ->setMethods(['isSpecificInstallmentAvailable'])
                                         ->getMock();
        $mockAvailPaymenteService
            ->method('isSpecificInstallmentAvailable')
            ->will($this->returnValue(false));

        /** @var PaymentGateway|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentGateway::class)
                    ->disableOriginalConstructor()
                    ->setMethods([
                        'gatherIBANandBIC',
                        'getValidateBankAccountService',
                        'getAvailablePaymentMethodsService',
                    ])
                    ->getMock();
        $sut->getSession()->setVariable('dynvalue', ['afterpayInstallmentProfileId' => 1]);

        $sut->method('gatherIBANandBIC')->will($this->returnValue([111, 222]));
        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));

        $this->assertFalse($sut->handleInstallment(oxNew(Order::class)));
    }

    /**
     *
     */
    public function testhandleDebitNoteOk()
    {

        $iban = new stdClass();
        $iban->name = 'apdebitbankaccount';
        $iban->value = 111;
        $bic = new stdClass();
        $bic->name = 'apdebitbankcode';
        $bic->value = 222;
        $dynValues = [$iban, $bic];
        $userPayment = oxNew(UserPayment::class);
        $userPayment->setDynValues($dynValues);

        $mockValidateService = $this->getMockBuilder(ValidateBankAccountService::class)
                                    ->disableOriginalConstructor()
                                    ->setMethods(['isValid'])
                                    ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(AvailablePaymentMethodsService::class)
                                         ->disableOriginalConstructor()
                                         ->setMethods(['isDirectDebitAvailable'])
                                         ->getMock();
        $mockAvailPaymenteService
            ->method('isDirectDebitAvailable')
            ->will($this->returnValue(true));


        /** @var PaymentGateway|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(PaymentGateway::class)
                    ->disableOriginalConstructor()
                    ->setMethods([
                        'getValidateBankAccountService',
                        'getAvailablePaymentMethodsService',
                    ])
                    ->getMock();
        $sut->setPaymentParams($userPayment);

        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));

        $this->assertEquals(12345, $sut->handleDebitNote(oxNew(Order::class)));
    }


    /**
     * SUT generator
     * Asserts that dereferToOtherPaymentProviders() only gets called if that is not an afterpay order
     *
     * @param $isAfterpayOrder
     * @param $success
     *
     * @return PaymentGateway|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSUT($isAfterpayOrder, $success)
    {
        $expectDereferToOtherPaymentProviders = !$isAfterpayOrder ? $this->once() : $this->never();
        $expectGetServiceCall = $isAfterpayOrder ? $this->once() : $this->never();

        $mockService = $this->getMockedService($isAfterpayOrder, $success);

        $sut = $this->getMockBuilder(PaymentGateway::class)
                    ->disableOriginalConstructor()
                    ->setMethods(['dereferToOtherPaymentProviders', 'getAuthorizePaymentService'])
                    ->getMock();

        $sut->expects($expectDereferToOtherPaymentProviders)
            ->method('dereferToOtherPaymentProviders')
            ->will($this->returnValue('DEREFERED'));

        $sut->expects($expectGetServiceCall)
            ->method('getAuthorizePaymentService')
            ->will($this->returnValue($mockService));

        return $sut;
    }

    /**
     * @param $isAfterpayOrder
     *
     * @return ArvatoOrder|PHPUnit_Framework_MockObject_MockObject - mocked
     */
    protected function getMockedOrder($isAfterpayOrder)
    {
        /** @var ArvatoOrder|PHPUnit_Framework_MockObject_MockObject $mockOxOrder */
        $mockOxOrder = $this->getMockBuilder(Order::class)
                            ->setMethods(['isAfterpayPaymentType'])
                            ->getMock();
        $mockOxOrder->expects($this->once())
                    ->method('isAfterpayPaymentType')
                    ->will($this->returnValue($isAfterpayOrder));
        $mockOxOrder->assign(['oxpaymenttype' => $isAfterpayOrder ? 'afterpayinvoice' : 'SomethingElse']);

        return $mockOxOrder;
    }

    /**
     * @param $isAfterpayOrder
     * @param $success
     *
     * @return AuthorizePaymentService|PHPUnit_Framework_MockObject_MockObject mocked
     */
    protected function getMockedService($isAfterpayOrder, $success)
    {
        $expectAuthorizedPaymentCall = $isAfterpayOrder ? $this->once() : $this->never();
        $expectGetErrorMessagesCall = ($isAfterpayOrder && !$success) ? $this->once() : $this->never();

        $mockService = $this->getMockBuilder(AuthorizePaymentService::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['authorizePayment', 'getErrorMessages'])
                            ->getMock();

        $mockService->expects($expectAuthorizedPaymentCall)
                    ->method('authorizePayment')
                    ->will($this->returnValue($success ? 'Accepted' : 'FooBar!'));

        $mockService->expects($expectGetErrorMessagesCall)
                    ->method('getErrorMessages')
                    ->will($this->returnValue('SomeErrorMessage'));

        return $mockService;
    }
}
