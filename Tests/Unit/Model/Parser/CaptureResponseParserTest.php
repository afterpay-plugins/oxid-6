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
 * Class CaptureResponseParserTest: Tests for CaptureResponseParser.
 */
class CaptureResponseParserTest extends \OxidEsales\TestingLibrary\UnitTestCase
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
        $object->capturedAmount = '111';
        $object->authorizedAmount = '222';
        $object->remainingAuthorizedAmount = '333';
        $object->captureNumber = '444';
        $object->message = '555';

        $sut = $this->getSUT();
        $sutReturn = $sut->parse($object);

        $this->assertEquals(111, $sutReturn->getCapturedAmount());
        $this->assertEquals(222, $sutReturn->getAuthorizedAmount());
        $this->assertEquals([555], $sutReturn->getErrors());
    }

    /**
     * SUT generator
     *
     * @return CaptureResponseParser
     */
    protected function getSUT()
    {
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser\CaptureResponseParser::class);
    }
}
