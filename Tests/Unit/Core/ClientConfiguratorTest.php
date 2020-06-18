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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class ClientConfiguratorTest: Tests for ClientConfigurator.
 */
class ClientConfiguratorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testGetAuthorizePaymentClient()
    {
        $sut = $this->getSUT();
        $sutReturn = $sut->getAuthorizePaymentClient();
        $this->assertEquals(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::FUNCTION_AUTHORIZE_CHECKOUT, $sutReturn->getFunction());
    }

    public function testgetCaptureClientEx()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException::class);
        $sut = $this->getSUT();
        $sut->getCaptureClient(0, 'SomeApiKey');
    }

    public function testGetCaptureClient()
    {
        $sut = $this->getSUT();
        $sutReturn = $sut->getCaptureClient('orderid123', 'SomeApiKey');
        $expected = sprintf(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::FUNCTION_CAPTURE, 'orderid123');
        $this->assertEquals($expected, $sutReturn->getFunction());
    }

    public function testgetCaptureShippingClientEx01()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException::class);
        $sut = $this->getSUT();
        $sut->getCaptureShippingClient(0, 1);
    }

    public function testgetCaptureShippingClientEx10()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException::class);
        $sut = $this->getSUT();
        $sut->getCaptureShippingClient(1, 0);
    }

    public function testgetCaptureShippingClientOk()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getCaptureShippingClient(1, 1)
        );
    }

    public function testgetValidateBankAccountClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getValidateBankAccountClient()
        );
    }

    public function testgetRefundClientEx()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException::class);
        $sut = $this->getSUT();
        $sut->getRefundClient(0);
    }

    public function testgetRefundClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getRefundClient(1)
        );
    }

    public function testgetAvailablePaymentMethodsClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getAvailablePaymentMethodsClient()
        );
    }

    public function testgetCreateContractClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getCreateContractClient(123)
        );
    }

    public function testgetAvailableInstallmentPlansClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getAvailableInstallmentPlansClient()
        );
    }

    public function testgetBaseClientEx01()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException::class);
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getBaseClient(null, 'lorem')
        );
    }

    public function testgetBaseClientEx10()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException::class);
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getBaseClient('lorem', null)
        );
    }

    public function testgetBaseClientOkLive()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getBaseClient('lorem', 'ipsum')
        );
    }

    public function testgetBaseClientOkLiveFixedurl()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiUrl', 'http://lorem');
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getBaseClient('lorem', 'ipsum')
        );
    }

    public function testgetBaseClientOkSandbox()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiSandboxMode', true);
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            \OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class,
            $sut->getBaseClient('lorem', 'ipsum')
        );
    }

    public function testgetUserCountryCodeIdFromSessionAT()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->oxuser__oxcountryid = new \OxidEsales\Eshop\Core\Field('a7c40f6320aeb2ec2.72885259');
        Registry::getSession()->setUser($oUser);
        $sut = $this->getSUT();
        $this->assertEquals('AT', $sut->getUserCountryCodeIdFromSession());
    }

    public function testgetUserCountryCodeIdFromSessionCH()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->oxuser__oxcountryid = new \OxidEsales\Eshop\Core\Field('a7c40f6321c6f6109.43859248');
        Registry::getSession()->setUser($oUser);
        $sut = $this->getSUT();
        $this->assertEquals('CH', $sut->getUserCountryCodeIdFromSession());
    }

    public function testgetUserCountryCodeIdFromSessionDE()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->oxuser__oxcountryid = new \OxidEsales\Eshop\Core\Field('somethingelse');
        Registry::getSession()->setUser($oUser);
        $sut = $this->getSUT();
        $this->assertEquals('DE', $sut->getUserCountryCodeIdFromSession());
    }

    /**
     * SUT generator
     *
     * @return ClientConfigurator
     */
    protected function getSUT()
    {
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ClientConfigurator::class);
    }
}
