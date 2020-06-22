<?php

/**
 *
 */

/**
 * THIS TESTS ARE INTEGRATIONAL. THEY SEND REAL API CALLS AND WILL FAIL, UNLESS SUFFICIENT CREDENTIALS ARE PROVIDED.
 * THE CREDENTIALS MUST MATCH THE PUBLIC SANDBOX - INTERNAL SANDBOX CREDENTIALS WILL FAIL.
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

/**
 * Class HttpClientTest: Test class for HttpClient.
 */
class HttpClientTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Tests GET requests.
     */
    public function testexecuteHttpRequestGET()
    {
        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $data = $service->executeHttpRequest('GET', 'version');

        $this->assertEquals(
            json_decode('{"message":"Authorization has been denied for this request."}'),
            json_decode($data)
        );
    }

    /**
     * Tests POST requests.
     */
    public function testexecuteHttpRequestPOST()
    {
        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $data = $service->executeHttpRequest('POST', 'version', 'POST data');

        $this->assertTrue(
            $data == json_decode('{"message":"The requested resource does not support http method \'POST\'."}')
            || $data == json_decode('{"message":"Authorization has been denied for this request."}')
        );
    }

    /**
     * Tests POST requests.
     */
    public function testexecuteHttpRequestNotPOSTorGET()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class, 'Unknown httpMethod FOOBAR');
        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $service->executeHttpRequest('FOOBAR', 'version', 'POST data');
    }

    /**
     * Tests POST requests with headers.
     */
    public function testexecuteHttpRequestPostRequestWithHeaders()
    {
        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $service->setRequestHeaders(array('X-Auth-Key: ABCDEF'));
        $data = $service->executeHttpRequest('POST', 'version', 'POST data');

        $this->assertTrue(
            $data == json_decode('{"message":"The requested resource does not support http method \'POST\'."}')
            || $data == json_decode('{"message":"Authorization has been denied for this request."}')
        );
    }

    public function testexecuteHttpRequestBadurl()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class, 'Could not resolve host: nowhere', 6);

        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->executeHttpRequest('POST', 'http://nowhere/');
    }

    public function testexecuteHttpRequestNomethod()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class);

        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->executeHttpRequest(null, 'http://nowhere/');
    }

    public function testexecuteHttpRequestNourl()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class);

        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->executeHttpRequest('xxx', null);
    }

    public function testexecuteJsonRequestNomethod()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class);

        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->executeJsonRequest(null, 'http://nowhere/');
    }

    public function testexecuteJsonRequestNourl()
    {
        $this->setExpectedException(\Arvato\AfterpayModule\Core\Exception\CurlException::class);

        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->executeJsonRequest('xxx', null);
    }

    /**
     * Tests JSON requests.
     */
    public function testexecuteJsonRequest()
    {
        $service = oxNew(\Arvato\AfterpayModule\Core\HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $inputData = (object) ['data' => 'json'];
        $outputData = $service->executeJsonRequest('POST', 'version', $inputData);

        $this->assertTrue(
            $outputData == json_decode('{"message":"The requested resource does not support http method \'POST\'."}')
            || $outputData == json_decode('{"message":"Authorization has been denied for this request."}')
        );
    }
}
