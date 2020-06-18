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

namespace Arvato\AfterpayModule\Core;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class Logging: Logging helper class
 */
class Logging
{

    /**
    * Logger
    * @param array with Logger Obj
    */
    protected $_aLogger = [];

    /**
     * If enabled in module settings, log resuest and response to shoproot/log/AFTERPAY.log,
     *
     * @param string $request_data JSON-encoded request payload
     * @param string $response_data JSON-encoded request response
     * @param string $service_url URL
     * @param float $duration Duration of the call in seconds (i.e. 0.01 = 10 ms)
     */
    public function logRestRequest($request_data, $response_data, $service_url = '', $duration = 0.0)
    {
        if (!$this->isLoggingEnabled()) {
            return;
        }

        if ($service_url == 'lookup/installment-plans') {
            $response_data = substr($response_data, 0, 35);
            $response_data = str_replace("\n", "", $response_data);
            $response_data = str_replace("\r", "", $response_data);
            $response_data .= '...';
        }

        $logMessage = PHP_EOL . PHP_EOL . '---------------------------------' . PHP_EOL
            . date('d.m.Y H:i:s') . PHP_EOL
            . $service_url . ($service_url ? PHP_EOL : '')
            . ($duration ? "API response time: $duration s" . PHP_EOL : '')
            . $request_data . PHP_EOL . PHP_EOL
            . '---------' . PHP_EOL
            . 'Response:' . PHP_EOL
            . '---------' . PHP_EOL . PHP_EOL
            . $response_data . PHP_EOL;

        $oLogger = $this->getLogger('ARVATO Logger', 'AFTERPAY.log', 'NOTICE');
        call_user_func([$oLogger, 'notice'], $logMessage);
    }

    /**
     * If enabled in module settings, log resuest and response to shoproot/log/AFTERPAY.log,
     *
     * @param $request_data string JSON-encoded request payload
     * @param $response_data string JSON-encoded request response
     * @param $service_url string URL
     */
    public function logInstallation($message)
    {
        $logMessage = PHP_EOL . '---------------------------------' . PHP_EOL
            . date('d.m.Y H:i:s') . PHP_EOL
            . $_SERVER['QUERY_STRING'] . PHP_EOL
            . $message . PHP_EOL;

        $oLogger = $this->getLogger('ARVATO Logger', 'AFTERPAY.log', 'NOTICE');
        call_user_func([$oLogger, 'notice'], $logMessage);
    }

    /**
     * Returns boolean logging status
     *
     * @return bool
     */
    public function isLoggingEnabled()
    {
        return Registry::getConfig()->getConfigParam('arvatoAfterpayApiRequestLogging');
    }


    /**
    * get Logger
    *
    * @return Monolog\Logger
    */
    protected function getLogger(string $sLogger = null, string $sFile = null, string $sLogLevel = null)
    {
        $sLogger = (!empty($sLogger) ? $sLogger : 'OXID Logger');

        if (!array_key_exists($sLogger, $this->_aLogger)) {
            $sFile = (!empty($sFile) ? $sFile : 'oxideshop.log');

            $sPath = Registry::getConfig()->getLogsDir() . $sFile;

            if (!is_file($sPath)) {
                file_put_contents($sPath, '');
            }

            $sLogLevel = strtoupper($sLogLevel);
            $sLogLevel = ((!empty($sLogLevel) && defined("Logger::$sLogLevel")) ? constant("Logger::$sLogLevel") : Logger::DEBUG);

            $this->_aLogger[$sLogger] = new Logger($sLogger);
            $this->_aLogger[$sLogger]->pushHandler(new StreamHandler(
                $sPath,
                $sLogLevel
            ));
        }

        return $this->_aLogger[$sLogger];
    }
}
