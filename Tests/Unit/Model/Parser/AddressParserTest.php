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
class AddressParserTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testparse()
    {
        $object = new \stdClass();
        $object->countryCode = '1';
        $object->postalCode = '2';
        $object->street = '3';
        $object->streetNumber = '4';
        $object->streetNumberAdditional = '5';
        $object->postalPlace = '6';
        $object->careOf = '7';

        $sut = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser\AddressParser::class);
        $sutReturn = $sut->parse($object);
        $expected = '{"countryCode":"1","postalCode":"2","street":"3","streetNumber":"4","streetNumberAdditional":"5","postalPlace":"6","careOf":"7"}';
        $this->assertEquals($expected, json_encode($sutReturn->exportData()));
    }
}
