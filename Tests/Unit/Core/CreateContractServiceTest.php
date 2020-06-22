<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

class CreateContractServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testConstruct()
    {
        $sut = oxNew(\Arvato\AfterpayModule\Core\CreateContractService::class, [123]);
        $this->assertInstanceOf(\Arvato\AfterpayModule\Core\CreateContractService::class, $sut);
    }

    public function testExecuteRequestException()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\PaymentException::class);
        $sut = oxNew(\Arvato\AfterpayModule\Core\CreateContractService::class, [123]);
        $sut->executeRequest(null, null, null, null);
    }
}
