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
 * Class AuthorizePaymentServiceTest: Tests for AuthorizePaymentService.
 */
class AuthorizePaymentServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method AuthorizePayment - successfull
     * reservation ID, checkout ID and Accepted-outcome
     */
    public function testAuthorizePaymentSuccessfull()
    {
        // Build SUT : Get ResponseData, Language, and Session to inject results into.

        $responseData = json_decode(file_get_contents(
            Registry::getConfig()->getConfigParam('sShopDir')
            . '/modules/oxps/arvatoafterpay/Tests/Fixtures/1stepAuthPaymentSuccessResponse.json'
        ));

        $mockOxSession = $this->getMockOxSession();
        $mockOxSession
            ->expects($this->exactly(2))
            ->method('setVariable')
            ->withConsecutive(
                [$this->equalTo('arvatoAfterpayReservationId'), $this->equalTo('reservationId12345')],
                [$this->equalTo('arvatoAfterpayCheckoutId'), $this->equalTo('checkoutId12345')]
            );

        $oxLang = Registry::getLang();

        $sut = $this->getAuthorizePaymentServiceMockedSut($responseData, $mockOxSession, $oxLang);
        $this->assertEquals('Accepted', $sut->authorizePayment(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }

    /**
     * Testing method AuthorizePayment - unsuccessfull
     * No reservation ID, checkout ID or Accepted-outcome
     */
    public function testAuthorizePaymentUnsuccessfull()
    {
        $responseData = new \stdClass();

        $mockOxSession = $this->getMockOxSession();
        $mockOxSession->expects($this->exactly(2))
            ->method('setVariable')
            ->withConsecutive(
                [$this->equalTo('arvatoAfterpayReservationId'), $this->equalTo(null)],
                [$this->equalTo('arvatoAfterpayCheckoutId'), $this->equalTo(null)]
            );

        $oxLang = Registry::getLang();

        $sut = $this->getAuthorizePaymentServiceMockedSut($responseData, $mockOxSession, $oxLang);
        $this->assertNotEquals('Accepted', $sut->authorizePayment(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }

    public function testexecuteRequestFromSessionData()
    {

        $sut =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AuthorizePaymentService::class)
                ->setConstructorArgs([Registry::getSession(), Registry::getLang()])
                ->disableOriginalClone()
                ->setMethods(['getAuthorizePaymentClient', 'getAuthorizePaymentDataProvider'])
                ->getMock();

        // Client

        $mockClient =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class)
                //->disableOriginalConstructor()
                //->disableOriginalClone()
                ->setMethods(['execute'])
                ->getMock();

        $mockClient
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue('###OK###'));

        $sut
            ->expects($this->once())
            ->method('getAuthorizePaymentClient')
            ->will($this->returnValue($mockClient));

        // Data provider

        $mockDataProvider =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AuthorizePaymentDataProvider::class)
                ->setMethods(['getDataObject'])
                ->getMock();

        $mockDataProvider
            ->expects($this->once())
            ->method('getDataObject')
            ->will($this->returnValue(oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity::class)));

        $sut
            ->expects($this->once())
            ->method('getAuthorizePaymentDataProvider')
            ->will($this->returnValue($mockDataProvider));

        // run
        $this->assertEquals('###OK###', $sut->executeRequestFromSessionData(oxNew(\OxidEsales\Eshop\Application\Model\Order::class)));
    }

    public function testupdateCorrectedCustomerAddressNoAddress()
    {
        $sut = $this->getMockForUpdateCorrectedCustomerAddress(false, false, false);
        $sutReturn = $sut->updateCorrectedCustomerAddress([null], false);
        // Assertion in mocks method call counter
        $this->assertNull($sutReturn);
    }

    public function testupdateCorrectedCustomerAddressBillingAddress()
    {
        $oAddress = new \stdClass();
        $oAddress->lorem = 'ipsum';

        $sut = $this->getMockForUpdateCorrectedCustomerAddress(true, true, false);

        $sutReturn = $sut->updateCorrectedCustomerAddress([$oAddress], false);
        // Assertion in mocks method call counter
        $this->assertNull($sutReturn);
    }

    public function testupdateCorrectedCustomerAddressDeliveryAddress()
    {
        $oAddress = new \stdClass();
        $oAddress->lorem = 'ipsum';

        $sut = $this->getMockForUpdateCorrectedCustomerAddress(true, true, true);
        $sutReturn = $sut->updateCorrectedCustomerAddress([$oAddress], true);
        // Assertion in mocks method call counter
        $this->assertNull($sutReturn);
    }

    /**
     * @param float Captured Amount to be mocked
     *
     * @return AuthorizePaymentService
     */
    protected function getAuthorizePaymentServiceMockedSut(\stdClass $response, \OxidEsales\Eshop\Application\Core\Session $mockOxSession, \OxidEsales\Eshop\Core\Language $oxLang)
    {
        $mockAuthorizePaymentService =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AuthorizePaymentService::class)
                ->setConstructorArgs([$mockOxSession, $oxLang])
                ->disableOriginalClone()
                ->setMethods(array('executeRequestFromSessionData'))
                ->getMock();

        $mockAuthorizePaymentService
            ->expects($this->once())
            ->method('executeRequestFromSessionData')
            ->will($this->returnValue($response));

        return $mockAuthorizePaymentService;
    }

    /**
     * Return session Mock that tests if reservationId and checkoutId is stored correctly
     *
     * @return oxSession Mocked
     */
    protected function getMockOxSession()
    {
        return $this->getMockBuilder(\OxidEsales\Eshop\Core\Session::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->setMethods(['setVariable'])
                ->getMock();
    }

    /**
     * @param $bAddressFound
     * @param $bIsUserFound
     * @param $bIsDeliveryAddress
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockForUpdateCorrectedCustomerAddress($bAddressFound, $bIsUserFound, $bIsDeliveryAddress)
    {
        $mockOxAddress =
            $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\Address::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->setMethods(['save'])
                ->getMock();

        if ($bIsUserFound) {
            $mockOxUser =
                $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\User::class)
                    ->disableOriginalConstructor()
                    ->disableOriginalClone()
                    ->setMethods(['save', 'getSelectedAddress'])
                    ->getMock();
            $mockOxUser
                ->expects($bIsDeliveryAddress ? $this->never() : $this->once())
                ->method('save')
                ->will($this->returnValue(null));
            $mockOxUser
                ->expects(!$bIsDeliveryAddress ? $this->never() : $this->once())
                ->method('getSelectedAddress')
                ->will($this->returnValue($mockOxAddress));
        }

        $mockOxSession =
            $this->getMockBuilder(\OxidEsales\Eshop\Core\Session::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->setMethods(['getUser'])
                ->getMock();
        $mockOxSession
            ->expects($bAddressFound ? $this->once() : $this->never())
            ->method('getUser')
            ->will($this->returnValue($bIsUserFound ? $mockOxUser : null));

        return $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AuthorizePaymentService::class)
                ->setConstructorArgs([$mockOxSession, Registry::getLang()])
                ->disableOriginalClone()
                ->setMethods(['getAuthorizePaymentClient', 'getAuthorizePaymentDataProvider'])
                ->getMock();
    }
}
