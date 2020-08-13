<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Parser\AvailableInstallmentPlansResponseParser;
use OxidEsales\TestingLibrary\UnitTestCase;
use stdClass;

/**
 * Class AddressParserTest: Tests for AddressParserv.
 */
class AvailableInstallmentPlansResponseParserTest extends UnitTestCase
{
    public function testparse()
    {
        $object = new stdClass();
        $object->availableInstallmentPlans = [1, 2];
        $sut = oxNew(AvailableInstallmentPlansResponseParser::class);
        $sutReturn = $sut->parse($object);
        $expected = '{"availableInstallmentPlans":[1,2]}';
        $this->assertEquals($expected, json_encode($sutReturn->exportData()));
    }
}
