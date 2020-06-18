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

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

/**
 * Class AvailableInstallmentPlansDataProvider
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 *
 * @codeCoverageIgnore
 */
class OrderDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets an order request object from the basket.
     *
     * @param oxBasket $basket
     * @param $orderId
     * @param oxOrder $oOrder
     *
     * @return OrderEntity
     */
    public function getOrderSummaryByBasket(\OxidEsales\Eshop\Application\Model\Basket $basket, $orderId, \OxidEsales\Eshop\Application\Model\Order $oOrder)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderEntity::class);

        $fNettoSum = round($oOrder->getOrderNetSum(), 2);
        $fBruttoSum = round($oOrder->getTotalOrderSum(), 2);

        $dataObject->setTotalGrossAmount($fBruttoSum);
        $dataObject->setTotalNetAmount($fNettoSum);

        $dataObject->setCurrency($basket->getBasketCurrency()->name);
        $dataObject->setItems(oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\OrderItemDataProvider::class)->getOrderItemList($basket));
        $dataObject->setNumber((string)$orderId);

        return $dataObject;
    }

    /**
     * Gets an order request object from the oxorder.
     *
     * @param oxBasket $basket
     *
     * @return OrderEntity
     */
    public function getOrderSummaryByOxOrder(\OxidEsales\Eshop\Application\Model\Order $order)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderEntity::class);
        $dataObject->setTotalGrossAmount($order->oxorder__oxtotalbrutsum->value);
        $dataObject->setCurrency($order->getOrderCurrency()->name);
        $dataObject->setTotalNetAmount($order->oxorder__oxtotalnetsum->value);

        return $dataObject;
    }
}
