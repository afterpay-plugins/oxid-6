<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Core\ClientConfigurator;
use Arvato\AfterpayModule\Core\Exception\CurlException;
use Arvato\AfterpayModule\Core\WebServiceClient;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class ClientConfiguratorTest: Tests for ClientConfigurator.
 */
class ClientConfiguratorTest extends UnitTestCase
{

    public function testGetAuthorizePaymentClient()
    {
        $sut = $this->getSUT();
        $sutReturn = $sut->getAuthorizePaymentClient();
        $this->assertEquals(WebServiceClient::FUNCTION_AUTHORIZE_CHECKOUT, $sutReturn->getFunction());
    }

    /**
     * @throws CurlException
     */
    public function testGetCaptureClientEx()
    {
        $this->setExpectedException(CurlException::class);
        $sut = $this->getSUT();
        $sut->getCaptureClient(0, 'SomeApiKey');
    }

    /**
     * @throws CurlException
     */
    public function testGetCaptureClient()
    {
        $sut = $this->getSUT();
        $sutReturn = $sut->getCaptureClient('orderid123', 'SomeApiKey');
        $expected = sprintf(WebServiceClient::FUNCTION_CAPTURE, 'orderid123');
        $this->assertEquals($expected, $sutReturn->getFunction());
    }

    /**
     * @throws CurlException
     */
    public function testGetCaptureShippingClientEx01()
    {
        $this->setExpectedException(CurlException::class);
        $sut = $this->getSUT();
        $sut->getCaptureShippingClient(0, 1);
    }

    /**
     * @throws CurlException
     */
    public function testGetCaptureShippingClientEx10()
    {
        $this->setExpectedException(CurlException::class);
        $sut = $this->getSUT();
        $sut->getCaptureShippingClient(1, 0);
    }

    /**
     * @throws CurlException
     */
    public function testGetCaptureShippingClientOk()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getCaptureShippingClient(1, 1)
        );
    }

    public function testGetValidateBankAccountClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getValidateBankAccountClient()
        );
    }

    /**
     * @throws CurlException
     */
    public function testGetRefundClientEx()
    {
        $this->setExpectedException(CurlException::class);
        $sut = $this->getSUT();
        $sut->getRefundClient(0);
    }

    /**
     * @throws CurlException
     */
    public function testGetRefundClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getRefundClient(1)
        );
    }

    public function testGetAvailablePaymentMethodsClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getAvailablePaymentMethodsClient()
        );
    }

    public function testGetAvailableInstallmentPlansClient()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getAvailableInstallmentPlansClient()
        );
    }

    /**
     * @throws CurlException
     */
    public function testGetBaseClientEx01()
    {
        $this->setExpectedException(CurlException::class);
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getBaseClient(null, 'lorem')
        );
    }

    /**
     * @throws CurlException
     */
    public function testGetBaseClientEx10()
    {
        $this->setExpectedException(CurlException::class);
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getBaseClient('lorem', null)
        );
    }

    /**
     * @throws CurlException
     */
    public function testGetBaseClientOkLive()
    {
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getBaseClient('lorem', 'ipsum')
        );
    }

    /**
     * @throws CurlException
     */
    public function testGetBaseClientOkLiveFixedUrl()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiUrl', 'http://lorem');
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getBaseClient('lorem', 'ipsum')
        );
    }

    /**
     * @throws CurlException
     */
    public function testGetBaseClientOkSandbox()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiSandboxMode', true);
        $sut = $this->getSUT();
        $this->assertInstanceOf(
            WebServiceClient::class,
            $sut->getBaseClient('lorem', 'ipsum')
        );
    }

    public function testGetUserCountryCodeIdFromSessionAT()
    {
        $user = oxNew(User::class);
        $user->oxuser__oxcountryid = new Field('a7c40f6320aeb2ec2.72885259');
        Registry::getSession()->setUser($user);
        $sut = $this->getSUT();
        $this->assertEquals('AT', $sut->getUserCountryCodeIdFromSession());
    }

    public function testGetUserCountryCodeIdFromSessionCH()
    {
        $user = oxNew(User::class);
        $user->oxuser__oxcountryid = new Field('a7c40f6321c6f6109.43859248');
        Registry::getSession()->setUser($user);
        $sut = $this->getSUT();
        $this->assertEquals('CH', $sut->getUserCountryCodeIdFromSession());
    }

    public function testGetUserCountryCodeIdFromSessionDE()
    {
        $user = oxNew(User::class);
        $user->oxuser__oxcountryid = new Field('somethingelse');
        Registry::getSession()->setUser($user);
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
        return oxNew(ClientConfigurator::class);
    }
}
