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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Parser;

/**
 * Class AddressParserTest: Tests for AddressParserv.
 */
class AvailableInstallmentPlansResponseParserTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testparse()
    {
        $object = new \stdClass();
        $object->availableInstallmentPlans = [1,2];
        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser\AvailableInstallmentPlansResponseParser::class);
        $sutReturn = $sut->parse($object);
        $expected = '{"availableInstallmentPlans":[1,2]}';
        $this->assertEquals($expected, json_encode($sutReturn->exportData()));
    }
}
