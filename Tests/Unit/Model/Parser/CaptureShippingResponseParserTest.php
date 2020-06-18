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
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser\CaptureShippingResponseParser::class);
    }
}
