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

class AvailableInstallmentPlanServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testgetAvailableInstallmentPlans()
    {

        $sut =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\AvailableInstallmentPlansService::class)
                ->setMethods(['parseResponse', 'getAvailableInstallmentPlansClient'])
                ->getMock();

        // Client

        $mockClient =
            $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class)
                ->setMethods(['execute'])
                ->getMock();

        $mockClient
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue('###OK###'));

        // SUT

        $sut
            ->expects($this->once())
            ->method('getAvailableInstallmentPlansClient')
            ->will($this->returnValue($mockClient));

        $sut
            ->expects($this->once())
            ->method('parseResponse')
            ->will($this->returnValue('###OK###'));

        // run
        $this->assertEquals('###OK###', $sut->getAvailableInstallmentPlans(123));
    }
}
