<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Application\Model\Entity\ValidateBankAccountResponseEntity;
use Arvato\AfterpayModule\Core\ValidateBankAccountService;
use Arvato\AfterpayModule\Core\WebServiceClient;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ValidateBankAccountServiceTest extends UnitTestCase
{

    public function testValidate()
    {
        $client =
            $this->getMockBuilder(WebServiceClient::class)
                 ->setMethods(['execute'])
                 ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        /** @var ValidateBankAccountService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(ValidateBankAccountService::class)
                 ->setMethods(['getRequestData', 'getClient', 'parseResponse'])
                 ->getMock();
        $sut
            ->expects($this->once())
            ->method('getRequestData')
            ->will($this->returnValue(222));
        $sut
            ->expects($this->once())
            ->method('getClient')
            ->will($this->returnValue($client));

        $sut
            ->expects($this->once())
            ->method('parseResponse')
            ->will($this->returnValue('###OK###'));

        // run
        $this->assertEquals('###OK###', $sut->validate(123, 456));
    }

    public function testIsValidNosandboxNotvalid()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiMode', 'live');

        $client =
            $this->getMockBuilder(WebServiceClient::class)
                 ->setMethods(['execute'])
                 ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        /** @var ValidateBankAccountService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(ValidateBankAccountService::class)
                 ->setMethods(['getRequestData', 'getClient', 'parseResponse'])
                 ->getMock();
        $sut
            ->expects($this->once())
            ->method('getRequestData')
            ->will($this->returnValue(222));
        $sut
            ->expects($this->once())
            ->method('getClient')
            ->will($this->returnValue($client));

        $sut
            ->expects($this->once())
            ->method('parseResponse')
            ->will($this->returnValue('###OK###'));

        // run
        $this->assertEquals(false, $sut->isValid(123, 456));
    }

    public function testIsValidNosandboxValid()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiMode', 'live');

        $entity = oxNew(ValidateBankAccountResponseEntity::class);
        $entity->setIsValid('FOOBAR');

        $client =
            $this->getMockBuilder(WebServiceClient::class)
                 ->setMethods(['execute'])
                 ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        /** @var ValidateBankAccountService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(ValidateBankAccountService::class)
                 ->setMethods(['getRequestData', 'getClient', 'parseResponse'])
                 ->getMock();
        $sut
            ->expects($this->once())
            ->method('getRequestData')
            ->will($this->returnValue(222));
        $sut
            ->expects($this->once())
            ->method('getClient')
            ->will($this->returnValue($client));

        $sut
            ->expects($this->once())
            ->method('parseResponse')
            ->will($this->returnValue($entity));

        // run
        $this->assertEquals('FOOBAR', $sut->isValid(123, 456));
    }

    public function testIsValidSandbox()
    {
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiMode', 'sandbox');
        $sut = oxNew(ValidateBankAccountService::class);
        $this->assertTrue($sut->isValid(123, 456));
    }
}
