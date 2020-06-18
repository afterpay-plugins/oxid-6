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
 * Class CaptureService: Service for capturing autorized payments with AfterPay.
 */
class CaptureService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * Standard constructor.
     *
     * @param oxOrder $oxOrder
     *
     * @internal param oxSession $session
     * @internal param oxLang $lang
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $oxOrder)
    {
        $this->_oxOrder = $oxOrder;
        $this->_afterpayOrder = $oxOrder->getAfterpayOrder();
    }

    /**
     * Performs the capture call.
     * If successfull sets status to captured.
     *
     * @param $sRecordedApiKey
     *
     * @param array|null $aOrderItems
     *
     * @return CaptureResponseEntity
     */
    public function capture($sRecordedApiKey, array $aOrderItems = null)
    {
        $response = $this->executeRequestFromOrderData($sRecordedApiKey, $aOrderItems);

        $this->_entity = $this->parseResponse($response);

        $capturedAmout = $this->getEntity()->getCapturedAmount();
        $remainingAuthorizedAmount = $this->getEntity()->getRemainingAuthorizedAmount();
        $captureNumber = $this->getEntity()->getCaptureNumber();
        if (
            is_numeric($capturedAmout) && $capturedAmout > 0
            && is_numeric($remainingAuthorizedAmount)
        ) {
            $this->_afterpayOrder->setStatus(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::AFTERPAYSTATUS_CAPTURED, $captureNumber);
            $this->_afterpayOrder->save();
        }

        return $this->getEntity();
    }

    /**
     * @param $sRecordedApiKey
     *
     * @param array|null $aOrderItems
     *
     * @return stdClass|stdClass[]
     */
    protected function executeRequestFromOrderData($sRecordedApiKey, array $aOrderItems = null)
    {
        $data = $this->getCaptureDataForApi($aOrderItems);
        $client = $this->getCaptureClientForApi($sRecordedApiKey);
        return $response = $client->execute($data);
    }

    /////////////////////////////////////////////////////
    /// //UNIT TEST HELPER
    /// @CodeCoverageIgnoreStart

    /**
     * Elevating visibility
     *
     * @param $sRecordedApiKey
     *
     * @return stdClass|stdClass[]
     */
    public function testexecuteRequestFromOrderData($sRecordedApiKey)
    {
        return $this->executeRequestFromOrderData($sRecordedApiKey);
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param array|null $aOrderItems
     *
     * @return stdClass
     */
    protected function getCaptureDataForApi(array $aOrderItems = null)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\CaptureDataProvider::class)->getDataObject($this->_oxOrder, $aOrderItems)->exportData();
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param $sRecordedApiKey
     *
     * @return WebServiceClient
     */
    protected function getCaptureClientForApi($sRecordedApiKey)
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getCaptureClient($this->_oxOrder->oxorder__oxordernr->value, $sRecordedApiKey);
    }
}
