<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Parser;

/**
 * Class CaptureShippingResponseParserTest: Tests for CaptureShippingResponseParser.
 */
class CaptureShippingResponseParserTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method parse
     */
    public function testParse()
    {
        $object = new \stdClass();
        $object->shippingNumber = '111';
        $object->message = '222';
        $sut = $this->getSUT();
        $sutReturn = $sut->parse($object);

        $this->assertEquals(111, $sutReturn->getShippingNumber());
        $this->assertEquals([222], $sutReturn->getErrors());
    }

    /**
     * SUT generator
     *
     * @return CaptureShippingResponseParser
     */
    protected function getSUT()
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\Parser\CaptureShippingResponseParser::class);
    }
}
