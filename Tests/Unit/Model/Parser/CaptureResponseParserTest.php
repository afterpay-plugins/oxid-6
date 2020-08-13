<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Parser\CaptureResponseParser;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\TestingLibrary\UnitTestCase;
use stdClass;

/**
 * Class CaptureResponseParserTest: Tests for CaptureResponseParser.
 */
class CaptureResponseParserTest extends UnitTestCase
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
     *
     * @throws StandardException
     */
    public function testParse()
    {
        $object = new stdClass();
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
        return oxNew(CaptureResponseParser::class);
    }
}
