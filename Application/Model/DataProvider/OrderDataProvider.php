<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\OrderEntity;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;

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
     * @param Basket $basket
     * @param $orderId
     * @param Order $order
     *
     * @return OrderEntity
     */
    public function getOrderSummaryByBasket(\OxidEsales\Eshop\Application\Model\Basket $basket, $orderId, \OxidEsales\Eshop\Application\Model\Order $order)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderEntity::class);

        $nettoSum = round($order->getOrderNetSum(), 2);
        $bruttoSum = round($order->getTotalOrderSum(), 2);

        $dataObject->setTotalGrossAmount($bruttoSum);
        $dataObject->setTotalNetAmount($nettoSum);

        $dataObject->setCurrency($basket->getBasketCurrency()->name);
        $dataObject->setItems(oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\OrderItemDataProvider::class)->getOrderItemList($basket));
        $dataObject->setNumber((string)$orderId);

        return $dataObject;
    }

    /**
     * Gets an order request object from the oxorder.
     *
     * @param Basket $basket
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
