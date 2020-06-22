<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;

class ValidateBankAccountServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testValidate()
    {
        $client =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        $sut =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
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
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiSandboxMode', false);

        $client =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        $sut =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
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
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiSandboxMode', false);

        $entity = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\ValidateBankAccountResponseEntity::class);
        $entity->setIsValid('FOOBAR');

        $client =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        $sut =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class)
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
        Registry::getConfig()->setConfigParam('arvatoAfterpayApiSandboxMode', true);
        $sut = oxNew(\Arvato\AfterpayModule\Core\ValidateBankAccountService::class);
        $this->assertTrue($sut->isValid(123, 456));
    }
}
