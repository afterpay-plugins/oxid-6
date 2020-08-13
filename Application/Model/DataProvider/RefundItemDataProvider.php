<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity;
use OxidEsales\Eshop\Application\Model\BasketItem;

/**
 * Class RefundItemDataProvider: Data provider to convert backend refund item array to request objects
 */
class RefundItemDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{

    public function getRefundDataFromOrderItems($captureNumber, $orderItems)
    {
        if (!count($orderItems)) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('No valid refund itemd defined. They must contain a gross price at least.');
        }
        $refundEntity = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\RefundEntity::class);

        foreach ($orderItems as &$item) {
            unset($item->oxArticle);
        }
        $orderItems = array_values($orderItems);

        $refundEntity->setOrderItems($orderItems);
        $refundEntity->setCaptureNumber($captureNumber);

        return $refundEntity;
    }

    public function getRefundDataFromVatSplittedRefunds($captureNumber, $vatSplittedRefunds)
    {
        $items = $this->getRefundItemList($vatSplittedRefunds);

        if (!count($items)) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('No valid refund itemd defined. They must contain a gross price at least.');
        }
        $refundEntity = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\RefundEntity::class);
        $refundEntity->setItems($items);
        $refundEntity->setCaptureNumber($captureNumber);

        return $refundEntity;
    }

    /**
     * @param array $vatSplittedRefunds
     *
     * @return array OrderItemEntity[]
     */
    public function getRefundItemList(array $vatSplittedRefunds)
    {
        $list = [];
        foreach ($vatSplittedRefunds as $item) {
            $item = $this->getRefundItem($item);
            if ($item) {
                $list[] = $item;
            }
        }

        return $list;
    }

    /**
     * Transforms a basket item into an AfterPay order item.
     *
     * @param array $item
     *
     * @return OrderItemEntity
     */
    public function getRefundItem(array $item)
    {
        $vatPercent = $item['vatPercent'];
        $gross = (float)str_replace(',', '.', $item['grossUnitPrice']);

        if (!$gross) {
            return null;
        }

        $net = ($item['grossunitprice'] / (100 + $vatPercent)) * 100;
        $vat = $gross - $net;

        $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);

        $orderItem->setProductId($item['productId']);
        $orderItem->setGroupId($item['groupId']);
        $orderItem->setDescription($item['description']);

        $orderItem->setGrossUnitPrice($gross);
        $orderItem->setNetUnitPrice($net);
        $vat && $orderItem->setVatAmount($vat);
        $orderItem->setVatPercent($vatPercent);
        $orderItem->setQuantity($item['quantity']);

        // $orderItem->setVatAmount($item['']);
        return $orderItem;
    }
}
