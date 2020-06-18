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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model;

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
        $blIsAfterpayOrder = false;
        $blSuccess = false;

        $mockOxOrder = $this->getMockedOrder($blIsAfterpayOrder);
        $sut = $this->getSUT($blIsAfterpayOrder, $blSuccess);

        $sutReturn = $sut->executePayment(123.45, $mockOxOrder);
        $this->assertEquals('DEREFERED', $sutReturn);
    }

    /**
     * Testing method executePayment for non-accepted orders.
     * Majority of assertions is in the mocks! See expects-never / expects-once
     */
    public function testExecutePaymentAfterpayOrderNotAccepted()
    {
        $blIsAfterpayOrder = true;
        $blSuccess = false;

        $mockOxOrder = $this->getMockedOrder($blIsAfterpayOrder);
        $sut = $this->getSUT($blIsAfterpayOrder, $blSuccess);

        $sutReturn = $sut->executePayment(123.45, $mockOxOrder);
        $this->assertEquals(false, $sutReturn);
    }

    /**
     * Testing method executePayment for non-accepted orders.
     * Majority of assertions is in the mocks! See expects-never / expects-once
     */
    public function testExecutePaymentAfterpayOrderAccepted()
    {
        $blIsAfterpayOrder = true;
        $blSuccess = true;

        $mockOxOrder = $this->getMockedOrder($blIsAfterpayOrder);
        $sut = $this->getSUT($blIsAfterpayOrder, $blSuccess);

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

        $class = new \ReflectionClass(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\PaymentGateway::class);
        $method = $class->getMethod('gatherIBANandBIC');
        $method->setAccessible(true);

        $oUserpayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
        $oUserpayment->setDynValues($dynValues);
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\PaymentGateway::class);
        $sut->setPaymentParams($oUserpayment);
        $sutReturn = $method->invokeArgs($sut, []);

        $this->assertEquals([222, 111], $sutReturn);
    }

    public function testhandleInstallmentOk()
    {

        $mockValidateService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isValid'))
            ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSpecificInstallmentAvailable','getNumberOfInstallmentsByProfileId'])
            ->getMock();
        $mockAvailPaymenteService
            ->method('isSpecificInstallmentAvailable')
            ->will($this->returnValue(true));
        $mockAvailPaymenteService
            ->method('getNumberOfInstallmentsByProfileId')
            ->will($this->returnValue(9));

        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\PaymentGateway::class)
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

        $mockValidateService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isValid'))
            ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Service\AvailablePaymentMethodsService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSpecificInstallmentAvailable','getNumberOfInstallmentsByProfileId'])
            ->getMock();
        $mockAvailPaymenteService
            ->method('isSpecificInstallmentAvailable')
            ->will($this->returnValue(false));
        $mockAvailPaymenteService
            ->method('getNumberOfInstallmentsByProfileId')
            ->will($this->returnValue(9));

        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\PaymentGateway::class)
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
        $oUserpayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
        $oUserpayment->setDynValues($dynValues);

        $mockValidateService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isValid'))
            ->getMock();
        $mockValidateService
            ->method('isValid')
            ->will($this->returnValue(true));

        $mockAvailPaymenteService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Service\AvailablePaymentMethodsService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isDirectDebitAvailable'])
            ->getMock();
        $mockAvailPaymenteService
            ->method('isDirectDebitAvailable')
            ->will($this->returnValue(true));

        $mockCCService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CreateContractService::class)
            ->disableOriginalConstructor()
            ->setMethods(['createContract'])
            ->getMock();
        $mockCCService
            ->method('createContract')
            ->will($this->returnValue(12345));


        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\PaymentGateway::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getValidateBankAccountService',
                'getAvailablePaymentMethodsService',
                'getCreateContractService'
            ])
            ->getMock();
        $sut->setPaymentParams($oUserpayment);

        $sut->method('getValidateBankAccountService')->will($this->returnValue($mockValidateService));
        $sut->method('getAvailablePaymentMethodsService')->will($this->returnValue($mockAvailPaymenteService));
        $sut->method('getCreateContractService')->will($this->returnValue($mockCCService));

        $this->assertEquals(12345, $sut->handleDebitNote(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }




    /**
     * SUT generator
     * Asserts that dereferToOtherPaymentProviders() only gets called if that is not an afterpay order
     *
     * @param $blIsAfterpayOrder
     * @param $blSuccess
     *
     * @return PaymentGateway
     */
    protected function getSUT($blIsAfterpayOrder, $blSuccess)
    {
        $expectDereferToOtherPaymentProviders = !$blIsAfterpayOrder ? $this->once() : $this->never();
        $expectGetServiceCall = $blIsAfterpayOrder ? $this->once() : $this->never();

        $mockService = $this->getMockedService($blIsAfterpayOrder, $blSuccess);

        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\PaymentGateway::class)
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
     * @param $blIsAfterpayOrder
     *
     * @return oxOrder - mocked
     */
    protected function getMockedOrder($blIsAfterpayOrder)
    {
        $mockOxOrder = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\Order::class)
            ->setMethods(array('isAfterpayPaymentType'))
            ->getMock();
        $mockOxOrder->expects($this->once())
            ->method('isAfterpayPaymentType')
            ->will($this->returnValue($blIsAfterpayOrder));
        $mockOxOrder->oxorder__oxpaymenttype = new \OxidEsales\Eshop\Core\Field($blIsAfterpayOrder ? 'afterpayinvoice' : 'SomethingElse');
        return $mockOxOrder;
    }

    /**
     * @param $blIsAfterpayOrder
     * @param $blSuccess
     *
     * @return AuthorizePaymentService mocked
     */
    protected function getMockedService($blIsAfterpayOrder, $blSuccess)
    {
        $expectAuthorizedPaymentCall = $blIsAfterpayOrder ? $this->once() : $this->never();
        $expectGetErrorMessagesCall = ($blIsAfterpayOrder && !$blSuccess) ? $this->once() : $this->never();

        $mockService = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AuthorizePaymentService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('authorizePayment', 'getErrorMessages'))
            ->getMock();

        $mockService->expects($expectAuthorizedPaymentCall)
            ->method('authorizePayment')
            ->will($this->returnValue($blSuccess ? 'Accepted' : 'FooBar!'));

        $mockService->expects($expectGetErrorMessagesCall)
            ->method('getErrorMessages')
            ->will($this->returnValue('SomeErrorMessage'));

        return $mockService;
    }
}
