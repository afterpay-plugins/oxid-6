<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Core\Exception\CurlException;
use Arvato\AfterpayModule\Core\WebServiceClient;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class WebServiceClientTest: Tests for WebServiceClient.
 */
class WebServiceClientTest extends UnitTestCase
{


    /**
     * Testing method setFunction - nonvariable URL
     *
     * @throws CurlException
     */
    public function testSetFunctionNonVariableUrl()
    {
        $sut = $this->getSUT();
        $sut->setFunction('LoremIpsum123');
        $this->assertEquals('LoremIpsum123', $sut->getFunction());
    }


    /**
     * Testing method setFunction - nonvariable URL
     */
    public function testgetHttpMethod()
    {
        $sut = $this->getSutMockedForExecute();
        $sut->setHttpmethod('lorem');
        $this->assertEquals('lorem', $sut->getHttpmethod());
    }

    /**
     * Testing method setFunction - nonvariable URL
     *
     * @throws CurlException
     */
    public function testSetFunctionVariableUrl()
    {
        $sut = $this->getSUT();
        $sut->setFunction('LoremIpsum/%d', [123]);
        $this->assertEquals('LoremIpsum/123', $sut->getFunction());
    }

    /**
     * Testing method getFunction
     * Identical with the setter test
     *
     * @throws CurlException
     */
    public function testGetFunction()
    {
        $sut = $this->getSUT();
        $sut->setFunction('LoremIpsum123');
        $this->assertEquals('LoremIpsum123', $sut->getFunction());
    }

    /**
     * Testing method execute
     *
     * @throws CurlException
     */
    public function testExcecute()
    {
        // Build SUT

        $function = 'Lorem';
        $data = 'Ipsum';
        $return = 'dolor sit amet';
        $httpmethod = 'POST';
        $sut = $this->getSutMockedForExecute();
        $sut->setFunction($function);
        $sut->setHttpmethod($httpmethod);

        // Define Expectations and assertions

        $sut->expects($this->once())
            ->method('executeJsonRequest')
            ->with($httpmethod, $function, $data)
            ->will($this->returnValue($return));

        $this->assertEquals($return, $sut->execute($data));
    }

    /**
     * SUT generator
     *
     * @return WebServiceClient
     */
    protected function getSUT()
    {
        return oxNew(WebServiceClient::class);
    }

    /**
     * @return WebServiceClient|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSutMockedForExecute()
    {
        return $this->getMockBuilder(WebServiceClient::class)
                    ->setMethods(['executeJsonRequest'])
                    ->getMock();
    }
}
