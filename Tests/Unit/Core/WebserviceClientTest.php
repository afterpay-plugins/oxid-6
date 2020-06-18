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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Core;

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
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class);
    }

    /**
     * @return WebServiceClient
     */
    protected function getSutMockedForExecute()
    {
        return $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\WebServiceClient::class)
            ->setMethods(array('executeJsonRequest'))
            ->getMock();
    }
}
