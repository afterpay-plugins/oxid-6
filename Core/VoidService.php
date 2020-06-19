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
 * @author    ©2020 norisk GmbH
 * @link
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\Entity\VoidResponseEntity;
use OxidEsales\Eshop\Application\Model\Order;
use stdClass;

/**
 * Class VoidService: Service for voiding autorized payments with AfterPay.
 */
class VoidService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * Standard constructor.
     *
     * @param Order $order
     *
     * @internal param oxSession $session
     * @internal param oxLang $lang
     *
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $order)
    {
        $this->_oxOrder = $order;
        $this->_afterpayOrder = $order->getAfterpayOrder();
    }

    /**
     * Performs the void call.
     *
     * @param $recordedApiKey
     *
     * @param array|null $orderItems
     *
     * @return VoidResponseEntity
     */
    public function void($recordedApiKey, array $orderItems = null)
    {
        $response = $this->executeRequestFromOrderData($recordedApiKey, $orderItems);
        $this->_entity = $this->parseResponse($response);

        if (
            is_numeric($this->getEntity()->getTotalAuthorizedAmount())
            && is_numeric($this->getEntity()->getTotalCapturedAmount())
        ) {
            $this->_afterpayOrder->setStatus(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::AFTERPAYSTATUS_AUTHORIZATIONVOIDED);
            $this->_afterpayOrder->save();
        }

        return $this->getEntity();
    }

    /**
     * @param $recordedApiKey
     * @param array|null $orderItems
     *
     * @return stdClass|stdClass[]
     *
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    protected function executeRequestFromOrderData($recordedApiKey, array $orderItems = null)
    {
        $data = $this->getVoidDataForApi($orderItems);
        $client = $this->getVoidClientForApi($recordedApiKey);
        return $client->execute($data);
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
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
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
    protected function getVoidDataForApi(array $orderItems = null)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\VoidDataProvider::class)->getDataObject($this->_oxOrder, $orderItems)->exportData();
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     */
    protected function getVoidClientForApi($recordedApiKey)
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getVoidClient(
            $this->_oxOrder->oxorder__oxordernr->value,
            $recordedApiKey
        );
    }
}
