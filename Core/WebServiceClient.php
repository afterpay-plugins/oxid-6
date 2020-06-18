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
 * Class WebServiceClient
 */
class WebServiceClient extends \Arvato\AfterpayModule\Core\HttpClient
{
    /**
     * Constants for the REST-functions (i.e. the URL-paths)
     */
    public const FUNCTION_AUTHORIZE_CHECKOUT = 'checkout/authorize';
    public const FUNCTION_CAPTURE = 'orders/%s/captures';
    public const FUNCTION_VOID = 'orders/%s/voids';
    public const FUNCTION_CAPTURESHIPPING = 'orders/%s/captures/%s/shipping-details';
    public const FUNCTION_VALIDATEBANKACCOUNT = 'validate/bank-account';
    public const FUNCTION_REFUND = 'orders/%s/refunds';
    public const FUNCTION_AVAILABLEPAYMENTMETHODS = 'checkout/payment-methods';
    public const FUNCTION_CREATECONTRACT = 'checkout/%s/contract ';
    public const FUNCTION_AVAILABLEINSTALLMENTPLANS = 'lookup/installment-plans';
    public const FUNCTION_ORDERDETAILS = 'orders/%s';

    public const HTTPMETHOD_AUTHORIZE_CHECKOUT = 'POST';
    public const HTTPMETHOD_CAPTURE = 'POST';
    public const HTTPMETHOD_VOID = 'POST';
    public const HTTPMETHOD_CAPTURESHIPPING = 'POST';
    public const HTTPMETHOD_VALIDATEBANKACCOUNT = 'POST';
    public const HTTPMETHOD_REFUND = 'POST';
    public const HTTPMETHOD_AVAILABLEPAYMENTMETHODS = 'POST';
    public const HTTPMETHOD_CREATECONTRACT = 'POST';
    public const HTTPMETHOD_AVAILABLEINSTALLMENTPLANS = 'POST';
    public const HTTPMETHOD_ORDERDETAILS = 'GET';

    /**
     * @var string Url for the method relatively to the base url.
     */
    protected $_function = '';

    /**
     * @var string Url for the method relatively to the base url.
     */
    protected $_httpmethod = '';

    /**
     * @return string
     */
    public function getHttpmethod()
    {
        return $this->_httpmethod;
    }

    /**
     * @param string $httpmethod
     */
    public function setHttpmethod($httpmethod)
    {
        $this->_httpmethod = $httpmethod;
    }

    /**
     * Sets the function for the web service.
     *
     * @param string $function
     * @param array $sprintfArgs non-assoc array of arguments to be unpacked for sprintf, e.g. order-id for capture
     *
     * @throws CurlException
     */
    public function setFunction($function, array $sprintfArgs = null)
    {
        if (isset($sprintfArgs) && is_array($sprintfArgs) && count($sprintfArgs)) {
            foreach ($sprintfArgs as $k => $urlParameter) {
                if (!isset($urlParameter)) {
                    throw new \Arvato\AfterpayModule\Core\Exception\CurlException("Parameter $k for curl function $function was empty");
                }
            }

            if (1 === count($sprintfArgs)) {
                $function = sprintf($function, $sprintfArgs[0]);
            } elseif (2 === count($sprintfArgs)) {
                $function = sprintf($function, $sprintfArgs[0], $sprintfArgs[1]);
            }
        }
        $this->_function = $function;
    }

    /**
     * Gets the REST-function (i.e. the URL-path)
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->_function;
    }

    /**
     * Executes the request.
     *
     * @param mixed $data
     *
     * @return stdClass
     */
    public function execute($data = null)
    {
        return $this->executeJsonRequest($this->_httpmethod, $this->_function, $data);
    }
}
