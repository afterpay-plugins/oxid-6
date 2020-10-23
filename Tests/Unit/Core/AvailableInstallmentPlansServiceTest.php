<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Core\AvailableInstallmentPlansService;
use Arvato\AfterpayModule\Core\WebServiceClient;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class AvailableInstallmentPlanServiceTest extends UnitTestCase
{

    public function testgetAvailableInstallmentPlans()
    {
        /** @var AvailableInstallmentPlansService|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut =
            $this->getMockBuilder(AvailableInstallmentPlansService::class)
                 ->setMethods(['parseResponse', 'getAvailableInstallmentPlansClient'])
                 ->getMock();

        // Client

        $mockClient =
            $this->getMockBuilder(WebServiceClient::class)
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
