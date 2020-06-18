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
 * Class OrderDetailsService
 */
class OrderDetailsService extends \Arvato\AfterpayModule\Core\Service
{


    /**
     * Standard constructor.
     *
     * @param oxOrder $oxOrder
     *
     * @internal param oxSession $session
     * @internal param oxLang $lang
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $oxOrder)
    {
        $this->_oxOrder = $oxOrder;
        $this->_afterpayOrder = $oxOrder->getAfterpayOrder();
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
     * @param AfterpayOrder $AfterpayOrder
     *
     * @return WebServiceClient
     * @codeCoverageIgnore Mocked away
     */
    protected function getClient()
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getOrderDetailsClient(
            $this->_oxOrder->oxorder__oxordernr->value,
            $this->_afterpayOrder->getUsedApiKey()
        );
    }
}
