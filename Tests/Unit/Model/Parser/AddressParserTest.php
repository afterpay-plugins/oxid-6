<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Parser\AddressParser;
use OxidEsales\TestingLibrary\UnitTestCase;
use stdClass;

/**
 * Class AddressParserTest: Tests for AddressParserv.
 */
class AddressParserTest extends UnitTestCase
{
    public function testparse()
    {
        $object = new stdClass();
        $object->countryCode = '1';
        $object->postalCode = '2';
        $object->street = '3';
        $object->streetNumber = '4';
        $object->streetNumberAdditional = '5';
        $object->postalPlace = '6';
        $object->careOf = '7';

        $sut = oxNew(AddressParser::class);
        $sutReturn = $sut->parse($object);
        $expected = '{"countryCode":"1","postalCode":"2","street":"3","streetNumber":"4","streetNumberAdditional":"5","postalPlace":"6","careOf":"7"}';
        $this->assertEquals($expected, json_encode($sutReturn->exportData()));
    }
}
