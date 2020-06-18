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

class CreateContractServiceTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testConstruct()
    {
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CreateContractService::class, [123]);
        $this->assertInstanceOf(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CreateContractService::class, $sut);
    }

    public function testExecuteRequestException()
    {
        $this->setExpectedException(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\PaymentException::class);
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\CreateContractService::class, [123]);
        $sut->executeRequest(null, null, null, null);
    }
}
