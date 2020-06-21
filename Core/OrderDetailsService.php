<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity;
use OxidEsales\Eshop\Application\Model\Order;

/**
 * Class OrderDetailsService
 */
class OrderDetailsService extends \Arvato\AfterpayModule\Core\Service
{


    /**
     * Standard constructor.
     *
     * @param Order $order
     *
     * @internal param oxSession $session
     * @internal param oxLang $lang
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $order)
    {
        $this->_order = $order;
        $this->_afterpayOrder = $order->getAfterpayOrder();
    }

    /**
     * @return OrderDetailsResponseEntity
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    public function getOrderDetails()
    {
        $client = $this->getClient();
        $response = $client->execute();
        $this->_entity = $this->parseResponse($response);
        return $this->getEntity();
    }

    /**
     * @param AfterpayOrder $afterpayOrder
     *
     * @return WebServiceClient
     * @codeCoverageIgnore Mocked away
     */
    protected function getClient()
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getOrderDetailsClient(
            $this->_order->oxorder__oxordernr->value,
            $this->_afterpayOrder->getUsedApiKey()
        );
    }
}
