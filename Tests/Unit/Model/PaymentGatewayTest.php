<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model;

/**
 * Class PaymentGatewayTest: Tests for PaymentGateway.
 */
class PaymentGatewayTest extends \OxidEsales\TestingLibrary\UnitTestCase
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

    public function testgatherIBANandBIC()
    {
        $iban = new \stdClass();
        $iban->name = 'apinstallmentbankaccount';
        $iban->value = 111;
        $bic = new \stdClass();
        $bic->name = 'apinstallmentbankcode';
        $bic->value = 222;
        $dynValues = [$iban, $bic];

        $class = new \ReflectionClass(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class);
        $method = $class->getMethod('gatherIBANandBIC');
        $method->setAccessible(true);

        $userPayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
        $userPayment->setDynValues($dynValues);
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class);
        $sut->setPaymentParams($userPayment);
        $sutReturn = $method->invokeArgs($sut, []);

        $this->assertEquals([222, 111], $sutReturn);
    }

    public function testhandleInstallmentOk()
    {

        $mockValidateService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isValid'))
            ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSpecificInstallmentAvailable'])
            ->getMock();
        $mockAvailPaymenteService
            ->method('isSpecificInstallmentAvailable')
            ->will($this->returnValue(true));
        $mockAvailPaymenteService
            // ->method('getNumberOfInstallmentsByProfileId')
            ->will($this->returnValue(9));

        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'gatherIBANandBIC',
                'getValidateBankAccountService',
                'getAvailablePaymentMethodsService',
                'createContract'
            ])
            ->getMock();
        $sut->getSession()->setVariable('dynvalue', ['afterpayInstallmentProfileId' => 1]);

        $sut->method('gatherIBANandBIC')->will($this->returnValue([111, 222]));
        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));
        $sut->method('createContract')->will($this->returnValue(12345));

        $this->assertEquals(12345, $sut->handleInstallment(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }


    public function testhandleInstallmentNotavailable()
    {

        $mockValidateService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isValid'))
            ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\Service\AvailablePaymentMethodsService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSpecificInstallmentAvailable'])
            ->getMock();
        $mockAvailPaymenteService
            ->method('isSpecificInstallmentAvailable')
            ->will($this->returnValue(false));
        $mockAvailPaymenteService
           // ->method('getNumberOfInstallmentsByProfileId')
            ->will($this->returnValue(9));

        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'gatherIBANandBIC',
                'getValidateBankAccountService',
                'getAvailablePaymentMethodsService',
                'createContract'
            ])
            ->getMock();
        $sut->getSession()->setVariable('dynvalue', ['afterpayInstallmentProfileId' => 1]);

        $sut->method('gatherIBANandBIC')->will($this->returnValue([111, 222]));
        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));
        $sut->method('createContract')->will($this->returnValue(12345));

        $this->assertFalse($sut->handleInstallment(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }

    /**
     *
     */
    public function testhandleDebitNoteOk()
    {

        $iban = new \stdClass();
        $iban->name = 'apdebitbankaccount';
        $iban->value = 111;
        $bic = new \stdClass();
        $bic->name = 'apdebitbankcode';
        $bic->value = 222;
        $dynValues = [$iban, $bic];
        $userPayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
        $userPayment->setDynValues($dynValues);

        $mockValidateService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isValid'))
            ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\Service\AvailablePaymentMethodsService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isDirectDebitAvailable'])
            ->getMock();
        $mockAvailPaymenteService
            ->method('isDirectDebitAvailable')
            ->will($this->returnValue(true));

        $mockCCService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\CreateContractService::class)
            ->disableOriginalConstructor()
            ->setMethods(['createContract'])
            ->getMock();
        $mockCCService
            ->method('createContract')
            ->will($this->returnValue(12345));


        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getValidateBankAccountService',
                'getAvailablePaymentMethodsService'
            ])
            ->getMock();
        $sut->setPaymentParams($userPayment);

        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));

        $this->assertEquals(12345, $sut->handleDebitNote(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }




    /**
     * SUT generator
     * Asserts that dereferToOtherPaymentProviders() only gets called if that is not an afterpay order
     *
     * @param $isAfterpayOrder
     * @param $success
     *
     * @return PaymentGateway
     */
    protected function getSUT($isAfterpayOrder, $success)
    {
        $expectDereferToOtherPaymentProviders = !$isAfterpayOrder ? $this->once() : $this->never();
        $expectGetServiceCall = $isAfterpayOrder ? $this->once() : $this->never();

        $mockService = $this->getMockedService($isAfterpayOrder, $success);

        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\PaymentGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(array('dereferToOtherPaymentProviders', 'getAuthorizePaymentService'))
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
     * @return oxOrder - mocked
     */
    protected function getMockedOrder($isAfterpayOrder)
    {
        $mockOxOrder = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\Order::class)
            ->setMethods(array('isAfterpayPaymentType'))
            ->getMock();
        $mockOxOrder->expects($this->once())
            ->method('isAfterpayPaymentType')
            ->will($this->returnValue($isAfterpayOrder));
        $mockOxOrder->oxorder__oxpaymenttype = new \OxidEsales\Eshop\Core\Field($isAfterpayOrder ? 'afterpayinvoice' : 'SomethingElse');
        return $mockOxOrder;
    }

    /**
     * @param $isAfterpayOrder
     * @param $success
     *
     * @return AuthorizePaymentService mocked
     */
    protected function getMockedService($isAfterpayOrder, $success)
    {
        $expectAuthorizedPaymentCall = $isAfterpayOrder ? $this->once() : $this->never();
        $expectGetErrorMessagesCall = ($isAfterpayOrder && !$success) ? $this->once() : $this->never();

        $mockService = $this->getMockBuilder(\Arvato\AfterpayModule\Core\AuthorizePaymentService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('authorizePayment', 'getErrorMessages'))
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
