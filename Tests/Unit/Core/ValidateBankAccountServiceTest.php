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

class ValidateBankAccountServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testValidate()
    {
        $client =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        $sut =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
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
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        $sut =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
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

        $entity = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\ValidateBankAccountResponseEntity::class);
        $entity->setIsValid('FOOBAR');

        $client =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();
        $client
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(111));

        $sut =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class)
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
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\ValidateBankAccountService::class);
        $this->assertTrue($sut->isValid(123, 456));
    }
}
