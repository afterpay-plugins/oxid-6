<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureResponseEntity;
use OxidEsales\Eshop\Application\Model\Order;
use stdClass;

/**
 * Class CaptureService: Service for capturing autorized payments with AfterPay.
 */
class CaptureService extends \Arvato\AfterpayModule\Core\Service
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
     * Performs the capture call.
     * If successfull sets status to captured.
     *
     * @param $recordedApiKey
     *
     * @param array|null $orderItems
     *
     * @return CaptureResponseEntity
     */
    public function capture($recordedApiKey, array $orderItems = null)
    {
        $response = $this->executeRequestFromOrderData($recordedApiKey, $orderItems);

        $this->_entity = $this->parseResponse($response);

        $capturedAmount = $this->getEntity()->getCapturedAmount();
        $remainingAuthorizedAmount = $this->getEntity()->getRemainingAuthorizedAmount();
        $captureNumber = $this->getEntity()->getCaptureNumber();
        if (
            is_numeric($capturedAmount) && $capturedAmount > 0
            && is_numeric($remainingAuthorizedAmount)
        ) {
            $this->_afterpayOrder->setStatus(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::AFTERPAYSTATUS_CAPTURED, $captureNumber);
            $this->_afterpayOrder->save();
        }

        return $this->getEntity();
    }

    /**
     * @param $recordedApiKey
     *
     * @param array|null $orderItems
     *
     * @return stdClass|stdClass[]
     */
    protected function executeRequestFromOrderData($recordedApiKey, array $orderItems = null)
    {
        $data = $this->getCaptureDataForApi($orderItems);
        $client = $this->getCaptureClientForApi($recordedApiKey);
        return $response = $client->execute($data);
    }

    /////////////////////////////////////////////////////
    /// //UNIT TEST HELPER
    /// @CodeCoverageIgnoreStart

    /**
     * Elevating visibility
     *
     * @param $recordedApiKey
     *
     * @return stdClass|stdClass[]
     */
    public function testexecuteRequestFromOrderData($recordedApiKey)
    {
        return $this->executeRequestFromOrderData($recordedApiKey);
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param array|null $orderItems
     *
     * @return stdClass
     */
    protected function getCaptureDataForApi(array $orderItems = null)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\CaptureDataProvider::class)->getDataObject($this->_order, $orderItems)->exportData();
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     */
    protected function getCaptureClientForApi($recordedApiKey)
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getCaptureClient($this->_order->oxorder__oxordernr->value, $recordedApiKey);
    }
}
