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
 * Class CaptureShippingService: Service for capturing a shipping.
 */
class RefundService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * Standard constructor.
     *
     * @param Order $oxOrder
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
     * Performs the refund call.
     *
     * @param $vatSplittedRefunds
     * @param $sRecordedApiKey
     * @param array|null $aOrderItems
     * @param string $sCaptureNo Omit to use last recorded Capture No.
     *
     * @return RefundResponseEntity
     * @throws CurlException
     */
    public function refund($vatSplittedRefunds, $sRecordedApiKey, array $aOrderItems = null, $sCaptureNo = null)
    {

        if ($vatSplittedRefunds && $aOrderItems) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('Provide either $vatSplittedRefunds or $aOrderItems, not both');
        }

        if (!$vatSplittedRefunds && !$aOrderItems) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('vatSplittedRefunds and aOrderItems were empty');
        }

        if ($vatSplittedRefunds) {
            $response = $this->executeRequestFromVatSplittedRefundFields(
                $this->_oxOrder->oxorder__oxordernr->value,
                $sCaptureNo ?: $this->_afterpayOrder->getCaptureNo(),
                $vatSplittedRefunds,
                $sRecordedApiKey
            );
        } else {
            $response = $this->executeRequestFromOrderItems(
                $this->_oxOrder->oxorder__oxordernr->value,
                $sCaptureNo ?: $this->_afterpayOrder->getCaptureNo(),
                $aOrderItems,
                $sRecordedApiKey
            );
        }

        $this->_entity = $this->parseResponse($response);

        return $this->getEntity();
    }

    /**
     * @param string $sOrderNr
     * @param string $sLastCaptureId
     * @param array $vatSplittedRefunds
     *
     * @param $sRecordedApiKey
     *
     * @return mixed
     * @codeCoverageIgnore Untested, since it contains only mockled-away oxNew-Calls.
     */
    protected function executeRequestFromVatSplittedRefundFields(
        $sOrderNr,
        $sLastCaptureId,
        $vatSplittedRefunds,
        $sRecordedApiKey
    ) {
        $data = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)->getRefundDataFromVatSplittedRefunds(
            $sLastCaptureId,
            $vatSplittedRefunds
        )->exportData();
        $client = oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getRefundClient($sOrderNr, $sRecordedApiKey);
        return $client->execute($data);
    }

    /**
     * @param string $sOrderNr
     * @param string $sLastCaptureId
     * @param $aOrderItems
     * @param $sRecordedApiKey
     *
     * @return mixed
     * @internal param array $vatSplittedRefunds
     *
     * @codeCoverageIgnore Untested, since it contains only mockled-away oxNew-Calls.
     */
    protected function executeRequestFromOrderItems(
        $sOrderNr,
        $sLastCaptureId,
        $aOrderItems,
        $sRecordedApiKey
    ) {
        $data = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)->getRefundDataFromOrderItems(
            $sLastCaptureId,
            $aOrderItems
        )->exportData();
        $client = oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getRefundClient($sOrderNr, $sRecordedApiKey);
        return $client->execute($data);
    }
}
