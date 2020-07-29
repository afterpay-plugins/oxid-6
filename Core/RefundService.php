<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\Entity\RefundResponseEntity;
use Arvato\AfterpayModule\Core\Exception\CurlException;
use OxidEsales\Eshop\Application\Model\Order;

/**
 * Class CaptureShippingService: Service for capturing a shipping.
 */
class RefundService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * Standard constructor.
     *
     * @param Order $order
     *
     * @internal param oxSession $session
     * @internal param oxLang $lang
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $order)
    {
        $this->_order = $order;
        $this->_afterpayOrder = $order->getAfterpayOrder();
    }

    /**
     * Performs the refund call.
     *
     * @param $vatSplittedRefunds
     * @param $recordedApiKey
     * @param array|null $orderItems
     * @param string $captureNo Omit to use last recorded Capture No.
     *
     * @return RefundResponseEntity
     * @throws CurlException
     */
    public function refund($vatSplittedRefunds, $recordedApiKey, array $orderItems = null, $captureNo = null)
    {

        if ($vatSplittedRefunds && $orderItems) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('Provide either $vatSplittedRefunds or $orderItems, not both');
        }

        if (!$vatSplittedRefunds && !$orderItems) {
            throw new \Arvato\AfterpayModule\Core\Exception\CurlException('vatSplittedRefunds and aOrderItems were empty');
        }

        if ($vatSplittedRefunds) {
            $response = $this->executeRequestFromVatSplittedRefundFields(
                $this->_order->oxorder__oxordernr->value,
                $captureNo ?: $this->_afterpayOrder->getCaptureNo(),
                $vatSplittedRefunds,
                $recordedApiKey
            );
        } else {
            $response = $this->executeRequestFromOrderItems(
                $this->_order->oxorder__oxordernr->value,
                $captureNo ?: $this->_afterpayOrder->getCaptureNo(),
                $orderItems,
                $recordedApiKey
            );
        }

        $this->_entity = $this->parseResponse($response);

        return $this->getEntity();
    }

    /**
     * @param string $orderNr
     * @param string $lastCaptureId
     * @param array $vatSplittedRefunds
     *
     * @param $recordedApiKey
     *
     * @return mixed
     * @codeCoverageIgnore Untested, since it contains only mockled-away oxNew-Calls.
     */
    protected function executeRequestFromVatSplittedRefundFields(
        $orderNr,
        $lastCaptureId,
        $vatSplittedRefunds,
        $recordedApiKey
    ) {
        $data = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)->getRefundDataFromVatSplittedRefunds(
            $lastCaptureId,
            $vatSplittedRefunds
        )->exportData();
        $client = oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getRefundClient($orderNr, $recordedApiKey);
        return $client->execute($data);
    }

    /**
     * @param string $orderNr
     * @param string $lastCaptureId
     * @param $orderItems
     * @param $recordedApiKey
     *
     * @return mixed
     * @internal param array $vatSplittedRefunds
     *
     * @codeCoverageIgnore Untested, since it contains only mockled-away oxNew-Calls.
     */
    protected function executeRequestFromOrderItems(
        $orderNr,
        $lastCaptureId,
        $orderItems,
        $recordedApiKey
    ) {
        $data = oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\RefundItemDataProvider::class)->getRefundDataFromOrderItems(
            $lastCaptureId,
            $orderItems
        )->exportData();
        $client = oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getRefundClient($orderNr, $recordedApiKey);
        return $client->execute($data);
    }
}
