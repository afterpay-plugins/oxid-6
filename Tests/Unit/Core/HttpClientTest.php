<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * THIS TESTS ARE INTEGRATION TESTS. THEY SEND REAL API CALLS AND WILL FAIL, UNLESS SUFFICIENT CREDENTIALS ARE PROVIDED.
 * THE CREDENTIALS MUST MATCH THE PUBLIC SANDBOX - INTERNAL SANDBOX CREDENTIALS WILL FAIL.
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Core\Exception\CurlException;
use Arvato\AfterpayModule\Core\HttpClient;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class HttpClientTest: Test class for HttpClient.
 */
class HttpClientTest extends UnitTestCase
{
    /**
     * Tests GET requests.
     */
    public function testExecuteHttpRequestGET()
    {
        $service = oxNew(HttpClient::class);
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
    public function testExecuteHttpRequestPOST()
    {
        $service = oxNew(HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $data = $service->executeHttpRequest('POST', 'version', 'POST data');
        $data = json_decode($data);

        $this->assertTrue(
            $data == json_decode('{"message":"The requested resource does not support http method \'POST\'."}')
            || $data == json_decode('{"message":"Authorization has been denied for this request."}')
        );
    }

    /**
     * Tests POST requests.
     */
    public function testExecuteHttpRequestNotPOSTorGET()
    {
        $this->expectException(CurlException::class, 'Unknown httpMethod FOOBAR');
        $service = oxNew(HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $service->executeHttpRequest('FOOBAR', 'version', 'POST data');
    }

    /**
     * Tests POST requests with headers.
     */
    public function testExecuteHttpRequestPostRequestWithHeaders()
    {
        $service = oxNew(HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $service->setRequestHeaders(['X-Auth-Key: ABCDEF']);
        $data = $service->executeHttpRequest('POST', 'version', 'POST data');
        $data = json_decode($data);

        $this->assertTrue(
            $data == json_decode('{"message":"The requested resource does not support http method \'POST\'."}')
            || $data == json_decode('{"message":"Authorization has been denied for this request."}')
        );
    }

    public function testExecuteHttpRequestBadUrl()
    {
        $this->expectException(CurlException::class, 'Could not resolve host: nowhere', 6);

        $service = oxNew(HttpClient::class);
        $service->executeHttpRequest('POST', 'http://nowhere/');
    }

    public function testExecuteHttpRequestNoMethod()
    {
        $this->expectException(CurlException::class);

        $service = oxNew(HttpClient::class);
        $service->executeHttpRequest(null, 'http://nowhere/');
    }

    public function testExecuteHttpRequestNoUrl()
    {
        $this->expectException(CurlException::class);

        $service = oxNew(HttpClient::class);
        $service->executeHttpRequest('xxx', null);
    }

    public function testExecuteJsonRequestNoMethod()
    {
        $this->expectException(CurlException::class);

        $service = oxNew(HttpClient::class);
        $service->executeJsonRequest(null, 'http://nowhere/');
    }

    public function testExecuteJsonRequestNoUrl()
    {
        $this->expectException(CurlException::class);

        $service = oxNew(HttpClient::class);
        $service->executeJsonRequest('xxx', null);
    }

    /**
     * Tests JSON requests.
     */
    public function testExecuteJsonRequest()
    {
        $service = oxNew(HttpClient::class);
        $service->setBaseUrl('https://sandbox.afterpay.io/api/v3/');
        $inputData = (object) ['data' => 'json'];
        $outputData = $service->executeJsonRequest('POST', 'version', $inputData);

        $this->assertTrue(
            $outputData == json_decode('{"message":"The requested resource does not support http method \'POST\'."}')
            || $outputData == json_decode('{"message":"Authorization has been denied for this request."}')
        );
    }
}
