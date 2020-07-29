<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

/**
 * Class WebServiceClientTest: Tests for WebServiceClient.
 */
class WebServiceClientTest extends \OxidEsales\TestingLibrary\UnitTestCase
{


    /**
     * Testing method setFunction - nonvariable URL
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
     */
    public function testGetFunction()
    {
        $sut = $this->getSUT();
        $sut->setFunction('LoremIpsum123');
        $this->assertEquals('LoremIpsum123', $sut->getFunction());
    }

    /**
     * Testing method excecute
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
        return oxNew(\Arvato\AfterpayModule\Core\WebServiceClient::class);
    }

    /**
     * @return WebServiceClient
     */
    protected function getSutMockedForExecute()
    {
        return $this->getMockBuilder(\Arvato\AfterpayModule\Core\WebServiceClient::class)
            ->setMethods(array('executeJsonRequest'))
            ->getMock();
    }
}
