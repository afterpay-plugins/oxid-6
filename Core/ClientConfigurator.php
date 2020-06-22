<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Core\Exception\CurlException;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class ClientConfigurator: Configures a client for the AfterPay webservice.
 */
class ClientConfigurator
{
    /**
     * Saves used API key to session, ensuring that subsequent calls will be made to the same API key.
     * It also enables saving of API key into AfterpayOrder table for backend usage
     * @param $isInstallmentApi
     */
    public function saveApiKeyToSession($isInstallmentApi)
    {
        list($url, $key) = $this->getApiCredentials($isInstallmentApi);

        if (!isAdmin()) {
            Registry::getSession()->setVariable('arvatoAfterpayApiKey', $key);
        }
    }

    /**
     * Returns a configured webservice client for the authorize payment webservice.
     *
     * @return WebServiceClient
     */
    public function getAuthorizePaymentClient()
    {
        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_AUTHORIZE_CHECKOUT,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_AUTHORIZE_CHECKOUT,
            null
        );
    }

    /**
     * Returns a configured webservice client for the capture webservice.
     *
     * @param $orderNr
     *
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     * @throws CurlException
     * @internal param $isInstallmentApi
     * @internal param $recordedApiKey
     *
     */
    public function getCaptureClient($orderNr, $recordedApiKey)
    {
        if (!$orderNr) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('sOrderNr was empty');
        }

        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_CAPTURE,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_CAPTURE,
            [$orderNr],
            null,
            $recordedApiKey
        );
    }

    /**
     * Returns a configured webservice client for the void webservice.
     *
     * @param $orderNr
     *
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     * @throws CurlException
     * @internal param $isInstallmentApi
     * @internal param $recordedApiKey
     *
     * @codeCoverageIgnore Live API connection - Mocked away in tests
     *
     */
    public function getVoidClient($orderNr, $recordedApiKey)
    {
        if (!$orderNr) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('sOrderNr was empty');
        }

        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_VOID,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_VOID,
            [$orderNr],
            null,
            $recordedApiKey
        );
    }

    /**
     * Returns a configured webservice client for the GetOrder webservice.
     * @url https://developer.afterpay.io/api/method/ordermanagement/getorder
     *
     * @param $orderNr
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     * @throws CurlException
     *
     * @codeCoverageIgnore Live API connection - Mocked away in tests
     *
     */
    public function getOrderDetailsClient($orderNr, $recordedApiKey)
    {
        if (!$orderNr) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('sOrderNr was empty');
        }
        if (!$recordedApiKey) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('$recordedApiKey was empty');
        }

        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_ORDERDETAILS,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_ORDERDETAILS,
            [$orderNr],
            null,
            $recordedApiKey
        );
    }

    /**
     * Returns a configured webservice client for the capture shipping webservice.
     *
     * @param $orderNr
     * @param $lastCaptureId
     *
     * @param $isInstallmentApi
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     * @throws CurlException
     */
    public function getCaptureShippingClient(
        $orderNr,
        $lastCaptureId,
        $isInstallmentApi = false,
        $recordedApiKey = ''
    ) {
        if (!$orderNr) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('sOrderNr was empty');
        }
        if (!$lastCaptureId) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('lastCaptureId was empty');
        }

        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_CAPTURESHIPPING,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_CAPTURESHIPPING,
            [$orderNr, $lastCaptureId],
            $isInstallmentApi,
            $recordedApiKey
        );
    }

    /**
     * Returns a configured webservice client
     *
     * @param bool $isInstallmentApi
     * @param string $recordedApiKey
     *
     * @return WebServiceClient
     */
    public function getValidateBankAccountClient(
        $isInstallmentApi = false,
        $recordedApiKey = ''
    ) {
        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_VALIDATEBANKACCOUNT,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_VALIDATEBANKACCOUNT,
            null,
            $isInstallmentApi,
            $recordedApiKey
        );
    }

    /**
     * @param string $orderNr
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     * @throws CurlException
     */
    public function getRefundClient($orderNr, $recordedApiKey = '')
    {
        if (!$orderNr) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('sOrderNr was empty');
        }

        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_REFUND,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_REFUND,
            [$orderNr],
            null,
            $recordedApiKey
        );
    }

    /**
     * @param $isInstallmentApi
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     */
    public function getAvailablePaymentMethodsClient($isInstallmentApi = false, $recordedApiKey = '')
    {
        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_AVAILABLEPAYMENTMETHODS,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_AVAILABLEPAYMENTMETHODS,
            null,
            $isInstallmentApi,
            $recordedApiKey
        );
    }

    /**
     * @param $checkoutId
     *
     * @param $isInstallmentApi
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     */
    public function getCreateContractClient($checkoutId, $isInstallmentApi = false, $recordedApiKey = '')
    {
        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_CREATECONTRACT,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_CREATECONTRACT,
            [$checkoutId],
            $isInstallmentApi,
            $recordedApiKey
        );
    }

    /**
     * @return WebServiceClient
     */
    public function getAvailableInstallmentPlansClient()
    {
        return $this->getBaseClient(
            \Arvato\AfterpayModule\Core\WebServiceClient::HTTPMETHOD_AVAILABLEINSTALLMENTPLANS,
            \Arvato\AfterpayModule\Core\WebServiceClient::FUNCTION_AVAILABLEINSTALLMENTPLANS,
            null,
            true
        );
    }

    /**
     * Get the function-agnostic client. Needs to get
     *
     * @param string $httpmethod POST or GET
     * @param string $function
     * @param array $sprintfArgs non-assoc array of arguments to be unpacked for sprintf, e.g. order-id for capture
     * @param bool $isInstallmentApi IsInstallmentApi
     * @param string $recordedApiKey RecorderdApiKey
     *
     * @return WebServiceClient
     * @throws CurlException
     */
    public function getBaseClient(
        $httpmethod,
        $function,
        $sprintfArgs = null,
        $isInstallmentApi = false,
        $recordedApiKey = ''
    ) {
        if (!$httpmethod) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('httpmethod was empty');
        }
        if (!$function) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('function was empty');
        }

        list($url, $key) = $this->getApiCredentials($isInstallmentApi, $recordedApiKey);

        $client = oxNew(\Arvato\AfterpayModule\Core\WebServiceClient::class);
        $client->setBaseUrl($url);
        $client->setHttpmethod($httpmethod);
        $client->setRequestHeaders(array(
            'X-Auth-Key: ' . $key,
            'Content-Type: application/json',
        ));
        $client->setFunction($function, $sprintfArgs);
        return $client;
    }

    /**
     * @param bool $isInstallmentApi
     * @param string $recordedApiKey
     *
     * @return array
     */
    protected function getApiCredentials(
        $isInstallmentApi = false,
        $recordedApiKey = ''
    ) {

        $isInstallmentApi = $isInstallmentApi ? 'Installment' : '';

        $customerCountryCode = $this->getUserCountryCodeIdFromSession();

        if (Registry::getConfig()->getConfigParam('arvatoAfterpayApiSandboxMode')) {
            $url = trim(Registry::getConfig()->getConfigParam('arvatoAfterpayApiSandboxUrl'));
            $key = trim(Registry::getConfig()->getConfigParam('arvatoAfterpayApiSandboxKey' . $customerCountryCode . $isInstallmentApi));
        } else {
            $url = trim(Registry::getConfig()->getConfigParam('arvatoAfterpayApiUrl'));
            $key = trim(Registry::getConfig()->getConfigParam('arvatoAfterpayApiKey' . $customerCountryCode . $isInstallmentApi));
        }

        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        $sessionApiKey = Registry::getSession()->getVariable('arvatoAfterpayApiKey');

        if ($recordedApiKey) {
            $key = $recordedApiKey;
        } elseif ($sessionApiKey && !isAdmin()) {
            $key = $sessionApiKey;
        }

        return [$url, $key];
    }

    /**
     * Return user country Code: DE, AT or CH.
     * If none is found, it will default to DE.
     * That way we have a valid API to communicate with and log,
     * while the API will take care of risk management etc.
     *
     * @return string $customerCountryCode DE, AT or CH
     */
    public function getUserCountryCodeIdFromSession()
    {

        $customerCountryId = Registry::getSession()->getUser()->oxuser__oxcountryid->value;

        switch ($customerCountryId) {
            case 'a7c40f6320aeb2ec2.72885259':
                $customerCountryCode = 'AT';
                break;
            case 'a7c40f6321c6f6109.43859248':
                $customerCountryCode = 'CH';
                break;
            case 'a7c40f632cdd63c52.64272623':
                $customerCountryCode = 'NL';
                break;
            case 'a7c40f631fc920687.20179984':
            default:
                $customerCountryCode = 'DE';
                break;
        }

        return $customerCountryCode;
    }
}
