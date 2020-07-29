<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Parser;

/**
 * Class AddressParserTest: Tests for AddressParserv.
 */
class AvailableInstallmentPlansResponseParserTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testparse()
    {
        $object = new \stdClass();
        $object->availableInstallmentPlans = [1,2];
        $sut = oxNew(\Arvato\AfterpayModule\Application\Model\Parser\AvailableInstallmentPlansResponseParser::class);
        $sutReturn = $sut->parse($object);
        $expected = '{"availableInstallmentPlans":[1,2]}';
        $this->assertEquals($expected, json_encode($sutReturn->exportData()));
    }
}
