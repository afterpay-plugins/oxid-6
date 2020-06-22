<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

class AvailableInstallmentPlanServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testgetAvailableInstallmentPlans()
    {

        $sut =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\AvailableInstallmentPlansService::class)
                ->setMethods(['parseResponse', 'getAvailableInstallmentPlansClient'])
                ->getMock();

        // Client

        $mockClient =
            $this->getMockBuilder(\Arvato\AfterpayModule\Core\WebServiceClient::class)
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
