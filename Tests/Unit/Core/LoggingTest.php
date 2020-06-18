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

use OxidEsales\Eshop\Core\Registry;

/**
 * Class LoggingTest: Test class for Logging.
 */
class LoggingTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Assert that with disabled logging no logfile is written
     */
    public function testLogRestRequestLoggingDisabled()
    {
        $sut = $this->getMokedSut(false);
        $sut->logRestRequest('abc', 'def', 'http://foobar');
        $this->assertFileNotExists($this->getLogfilePath());
    }

    /**
     * Assert that logfile with correct content is written, when enabled
     */
    public function testLogRestRequestLoggingEnabled()
    {
        $sut = $this->getMokedSut(true);
        $sut->logRestRequest('abc', 'def', 'http://foobar');
        $this->assertFileExists($this->getLogfilePath());

        $logfileContent = file_get_contents($this->getLogfilePath());

        $this->assertTrue(false !== strpos($logfileContent, 'abc'));
        $this->assertTrue(false !== strpos($logfileContent, 'def'));
        $this->assertTrue(false !== strpos($logfileContent, 'http://foobar'));
    }

    /**
     * Assert that logfile with correct content is written
     */
    public function testLogInstallation()
    {
        $sut = $this->getMokedSut(true, true);
        $sut->logInstallation('abc');
        $this->assertFileExists($this->getLogfilePath());

        $logfileContent = file_get_contents($this->getLogfilePath());

        $this->assertTrue(false !== strpos($logfileContent, 'abc'));
    }

    /**
     * Deletes AFTERPAY.log, if existent
     */
    public function setUp()
    {
        if (file_exists($this->getLogfilePath())) {
            unlink($this->getLogfilePath());
        }
    }

    /**
     * @return string Logfile path
     */
    protected function getLogfilePath()
    {
        return Registry::getConfig()->getLogsDir() . 'AFTERPAY.log';
    }

    /**
     * Get mocked SUT for config injection
     * @param bool $loggingEnabled
     * @return Logging
     */
    protected function getMokedSut($loggingEnabled, $willOverrideLoggingStatus = false)
    {
        $sut = $this->getMockBuilder(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Logging::class)
            ->setMethods(array('isLoggingEnabled'))
            ->getMock();
        $sut->expects($willOverrideLoggingStatus ? $this->never() : $this->once())
            ->method('isLoggingEnabled')
            ->will($this->returnValue((bool)$loggingEnabled));

        return $sut;
    }


    /**
     * Asserts that logging status did not become hardcoded
     */
    public function testisLoggingEnabled()
    {
        $this->assertEquals(
            Registry::getConfig()->getConfigParam('arvatoAfterpayApiRequestLogging'),
            oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Core\Logging::class)->isLoggingEnabled()
        );
    }
}
