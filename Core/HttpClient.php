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

/**
 * Class HttpClient: Client for the afterpay webservice.
 *
 * @codeCoverageIgnore Test class is disabled (dot in front of the filename):
 * THE TESTS ARE INTEGRATIONAL. THEY SEND REAL API CALLS AND WILL FAIL, UNLESS SUFFICIENT CREDENTIALS ARE PROVIDED.
 * THE CREDENTIALS MUST MATCH THE PUBLIC SANDBOX - INTERNAL SANDBOX CREDENTIALS WILL FAIL.
 */
class HttpClient
{
    /**
     * @var resource Curl session handle.
     */
    protected $_handle;

    /**
     * @var string[] Additional request headers.
     */
    protected $_requestHeaders = [];

    /**
     * @var string Base url for the request.
     */
    protected $_baseUrl;

    /**
     * Sets the base url for the service
     *
     * @param string $url
     */
    public function setBaseUrl($url)
    {
        $this->_baseUrl = $url;
    }

    /**
     * Sets the headers for the service requests.
     *
     * @param string[] $headers
     */
    public function setRequestHeaders(array $headers)
    {
        $this->_requestHeaders = $headers;
    }

    /**
     * Performs an request to a server with an JSON string.
     *
     * @param string $httpMethod
     * @param string $serviceUrl
     * @param mixed $data
     *
     * @return stdClass
     * @throws CurlException
     */
    public function executeJsonRequest($httpMethod, $serviceUrl, $data = null)
    {
        if (!$httpMethod) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('$httpMethod was empty');
        }
        if (!$serviceUrl) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('$serviceUrl was empty');
        }

        $encodedData = json_encode($data, JSON_PRETTY_PRINT);

        $startTime = microtime(true);
        $encodedResponse = $this->executeHttpRequest($httpMethod, $serviceUrl, $encodedData);
        $duration = microtime(true) - $startTime;
        $response = json_decode($encodedResponse);
        oxNew(\Arvato\AfterpayModule\Core\Logging::class)->logRestRequest($encodedData, $encodedResponse, $serviceUrl, $duration);
        return $response;
    }

    /**
     * Performs an HTTP request to a server.
     *
     * @param string $httpMethod
     * @param string $serviceUrl
     * @param string $data
     *
     * @return string
     * @throws CurlException
     */
    public function executeHttpRequest($httpMethod, $serviceUrl, $data = null)
    {
        if (!$httpMethod) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('$httpMethod was empty');
        }
        if (!$serviceUrl) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('$serviceUrl was empty');
        }

        $this->init($this->_baseUrl . $serviceUrl);

        if ('POST' == $httpMethod) {
            if (!empty($data)) {
                $this->setPostData($data);
            }
        } elseif ('GET' == $httpMethod) {
            $this->setGet();
        } else {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('Unknown httpMethod ' . $httpMethod);
        }

        $this->addHeaders();

        $response = $this->curlExec();
        $this->close();

        return $response;
    }

    /**
     * Adds additional headers for the request.
     *
     * @throws CurlException
     */
    protected function addHeaders()
    {
        curl_setopt($this->_handle, CURLOPT_HTTPHEADER, $this->_requestHeaders);
        $this->catchRequestError();
    }

    /**
     * Executes the curl request.
     *
     * @return string
     * @throws CurlException
     */
    protected function curlExec()
    {
        $response = curl_exec($this->_handle);
        $this->catchRequestError();

        return $response;
    }

    /**
     * Closes a curl session handle.
     */
    protected function close()
    {
        curl_close($this->_handle);
    }

    /**
     * Creates a curl session handle.
     *
     * @param $url
     *
     * @throws CurlException
     */
    protected function init($url)
    {
        $this->_handle = curl_init($url);
        $this->catchRequestError();
        curl_setopt($this->_handle, CURLOPT_RETURNTRANSFER, true);
        $this->catchRequestError();
    }

    /**
     * Sets the POST data for a curl request.
     *
     * @param string $data
     *
     * @throws CurlException
     */
    protected function setPostData($data)
    {
        curl_setopt($this->_handle, CURLOPT_POST, true);
        $this->catchRequestError();
        curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $data);
        $this->catchRequestError();
    }

    /**
     * Sets the method to GET.
     *
     * @throws CurlException
     */
    protected function setGet()
    {
        curl_setopt($this->_handle, CURLOPT_HTTPGET, true);
        $this->catchRequestError();
    }

    /**
     * Tests if there was an curl error.
     *
     * @throws CurlException
     */
    protected function catchRequestError()
    {
        if (curl_errno($this->_handle) != 0) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException(curl_error($this->_handle), curl_errno($this->_handle));
        }
    }
}
