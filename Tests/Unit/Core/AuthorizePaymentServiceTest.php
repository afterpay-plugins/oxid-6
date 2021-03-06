<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Application\Model\DataProvider\AuthorizePaymentDataProvider;
use Arvato\AfterpayModule\Application\Model\Entity\Entity;
use Arvato\AfterpayModule\Core\AuthorizePaymentService;
use Arvato\AfterpayModule\Core\WebServiceClient;
use OxidEsales\Eshop\Application\Model\Address;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

/**
 * Class AuthorizePaymentServiceTest: Tests for AuthorizePaymentService.
 */
class AuthorizePaymentServiceTest extends UnitTestCase
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
            . '/modules/arvato/afterpay/Tests/Fixtures/1stepAuthPaymentSuccessResponse.json'
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
        $this->assertEquals('Accepted', $sut->authorizePayment(oxNew(Order::class)));
    }

    /**
     * Testing method AuthorizePayment - unsuccessfull
     * No reservation ID, checkout ID or Accepted-outcome
     */
    public function testAuthorizePaymentUnsuccessfull()
    {
        $responseData = new stdClass();

        $mockOxSession = $this->getMockOxSession();
        $mockOxSession->expects($this->exactly(2))
                      ->method('setVariable')
                      ->withConsecutive(
                          [$this->equalTo('arvatoAfterpayReservationId'), $this->equalTo(null)],
                          [$this->equalTo('arvatoAfterpayCheckoutId'), $this->equalTo(null)]
                      );

        $oxLang = Registry::getLang();

        $sut = $this->getAuthorizePaymentServiceMockedSut($responseData, $mockOxSession, $oxLang);
        $this->assertNotEquals('Accepted', $sut->authorizePayment(oxNew(Order::class)));
    }

    public function testexecuteRequestFromSessionData()
    {
        /** @var AuthorizePaymentService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(AuthorizePaymentService::class)
                 ->setConstructorArgs([Registry::getSession(), Registry::getLang()])
                 ->disableOriginalClone()
                 ->setMethods(['getAuthorizePaymentClient', 'getAuthorizePaymentDataProvider'])
                 ->getMock();

        // Client

        $mockClient =
            $this->getMockBuilder(WebServiceClient::class)
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
            $this->getMockBuilder(AuthorizePaymentDataProvider::class)
                 ->setMethods(['getDataObject'])
                 ->getMock();

        $mockDataProvider
            ->expects($this->once())
            ->method('getDataObject')
            ->will($this->returnValue(oxNew(Entity::class)));

        $sut
            ->expects($this->once())
            ->method('getAuthorizePaymentDataProvider')
            ->will($this->returnValue($mockDataProvider));

        // run
        $this->assertEquals('###OK###', $sut->executeRequestFromSessionData(oxNew(Order::class)));
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
        $address = new stdClass();
        $address->lorem = 'ipsum';

        $sut = $this->getMockForUpdateCorrectedCustomerAddress(true, true, false);

        $sutReturn = $sut->updateCorrectedCustomerAddress([$address], false);
        // Assertion in mocks method call counter
        $this->assertNull($sutReturn);
    }

    public function testupdateCorrectedCustomerAddressDeliveryAddress()
    {
        $address = new stdClass();
        $address->lorem = 'ipsum';

        $sut = $this->getMockForUpdateCorrectedCustomerAddress(true, true, true);
        $sutReturn = $sut->updateCorrectedCustomerAddress([$address], true);
        // Assertion in mocks method call counter
        $this->assertNull($sutReturn);
    }

    /**
     * @param stdClass $response
     * @param Session  $mockOxSession
     * @param Language $oxLang
     * @return AuthorizePaymentService|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAuthorizePaymentServiceMockedSut(stdClass $response, Session $mockOxSession, Language $oxLang)
    {
        $mockAuthorizePaymentService =
            $this->getMockBuilder(AuthorizePaymentService::class)
                 ->setConstructorArgs([$mockOxSession, $oxLang])
                 ->disableOriginalClone()
                 ->setMethods(['executeRequestFromSessionData'])
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
     * @return Session|PHPUnit_Framework_MockObject_MockObject Mocked
     */
    protected function getMockOxSession()
    {
        return $this->getMockBuilder(Session::class)
                    ->disableOriginalConstructor()
                    ->disableOriginalClone()
                    ->setMethods(['setVariable'])
                    ->getMock();
    }

    /**
     * @param $addressFound
     * @param $isUserFound
     * @param $isDeliveryAddress
     *
     * @return AuthorizePaymentService|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockForUpdateCorrectedCustomerAddress($addressFound, $isUserFound, $isDeliveryAddress)
    {
        $mockOxAddress =
            $this->getMockBuilder(Address::class)
                 ->disableOriginalConstructor()
                 ->disableOriginalClone()
                 ->setMethods(['save'])
                 ->getMock();

        if ($isUserFound) {
            $mockOxUser =
                $this->getMockBuilder(User::class)
                     ->disableOriginalConstructor()
                     ->disableOriginalClone()
                     ->setMethods(['save', 'getSelectedAddress'])
                     ->getMock();
            $mockOxUser
                ->expects($isDeliveryAddress ? $this->never() : $this->once())
                ->method('save')
                ->will($this->returnValue(null));
            $mockOxUser
                ->expects(!$isDeliveryAddress ? $this->never() : $this->once())
                ->method('getSelectedAddress')
                ->will($this->returnValue($mockOxAddress));
        }

        $mockOxSession =
            $this->getMockBuilder(Session::class)
                 ->disableOriginalConstructor()
                 ->disableOriginalClone()
                 ->setMethods(['getUser'])
                 ->getMock();
        $mockOxSession
            ->expects($addressFound ? $this->once() : $this->never())
            ->method('getUser')
            ->will($this->returnValue($isUserFound ? $mockOxUser : null));

        return $this->getMockBuilder(AuthorizePaymentService::class)
                    ->setConstructorArgs([$mockOxSession, Registry::getLang()])
                    ->disableOriginalClone()
                    ->setMethods(['getAuthorizePaymentClient', 'getAuthorizePaymentDataProvider'])
                    ->getMock();
    }
}
