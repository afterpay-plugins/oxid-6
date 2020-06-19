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

use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use stdClass;

/**
 * Class CaptureShippingService: Service for capturing a shipping.
 */
class CaptureShippingService extends \Arvato\AfterpayModule\Core\Service
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
        $this->_oxOrder = $order;
        $this->_afterpayOrder = $order->getAfterpayOrder();
    }

    /**
     * Performs the capture shipping call.
     *
     * @param string $trackingId as provided by the carrier company
     * @param string $recordedApiKey
     * @param string $shippingCompany e.g. dhl, ups, dpd
     * @param string $type Shipment / Return
     *
     * @return CaptureShippingResponseEntity
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    public function captureShipping($trackingId, $recordedApiKey, $shippingCompany = 'Others', $type = 'Shipment')
    {

        if (!$this->_afterpayOrder->getCaptureNo()) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException(
                'Cannot capture shipping without capture payment id. Check if views are generated and tmp was cleared'
            );
        }

        $response = $this->executeRequestFromOrderData($this->_afterpayOrder, $trackingId, $recordedApiKey, $shippingCompany, $type);

        $this->_entity = $this->parseResponse($response);

        $shippingNumber = $this->getEntity()->getShippingNumber();

        if (is_numeric($shippingNumber) && $shippingNumber > 0) {
            $this->_oxOrder->oxorder__oxtrackcode = new \OxidEsales\Eshop\Core\Field($trackingId);
            $this->_oxOrder->oxorder__oxsenddate = new \OxidEsales\Eshop\Core\Field(
                date("Y-m-d H:i:s", Registry::getUtilsDate()->getTime())
            );
            $this->_oxOrder->save();
        }

        return $this->getEntity();
    }

    /**
     * @param AfterpayOrder $AfterpayOrder
     * @param string $trackingId as provided by the carrier company
     * @param $recordedApiKey
     * @param string $shippingCompany e.g. dhl, ups, dpd
     * @param string $type Shipment / Return
     *
     * @return stdClass|stdClass[]
     * @codeCoverageIgnore : Untested since we would have to mock away both lines
     */
    protected function executeRequestFromOrderData(
        \Arvato\AfterpayModule\Application\Model\AfterpayOrder $AfterpayOrder,
        $trackingId,
        $recordedApiKey,
        $shippingCompany,
        $type
    ) {
        $data = $this->getData($trackingId, $shippingCompany, $type);
        return $this->getClient($AfterpayOrder, $recordedApiKey)->execute($data);
    }

    /**
     * @param $trackingId
     * @param $shippingCompany
     * @param $type
     *
     * @return object
     * @codeCoverageIgnore Mocked away
     */
    protected function getData($trackingId, $shippingCompany, $type)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\CaptureShippingDataProvider::class)->getDataObject(
            $trackingId,
            $shippingCompany,
            $type
        )->exportData();
    }

    /**
     * @param AfterpayOrder $AfterpayOrder
     *
     * @param $recordedApiKey
     *
     * @return WebServiceClient
     * @codeCoverageIgnore Mocked away
     */
    protected function getClient(\Arvato\AfterpayModule\Application\Model\AfterpayOrder $AfterpayOrder, $recordedApiKey)
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getCaptureShippingClient(
            $AfterpayOrder->getOxOrder()->oxorder__oxordernr->value,
            $AfterpayOrder->arvatoafterpayafterpayorder__apcaptureno->value,
            null,
            $recordedApiKey
        );
    }
}
