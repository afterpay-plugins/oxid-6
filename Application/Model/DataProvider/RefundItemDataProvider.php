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
 * Class RefundItemDataProvider: Data provider to convert backend refund item array to request objects
 */
class RefundItemDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{

    public function getRefundDataFromOrderItems($captureNumber, $aOrderItems)
    {
        if (!count($aOrderItems)) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('No valid refund itemd defined. They must contain a gross price at least.');
        }
        $RefundEntity = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\RefundEntity::class);

        foreach ($aOrderItems as &$item) {
            unset($item->oxArticle);
        }
        $aOrderItems = array_values($aOrderItems);

        $RefundEntity->setOrderItems($aOrderItems);
        $RefundEntity->setCaptureNumber($captureNumber);

        return $RefundEntity;
    }

    public function getRefundDataFromVatSplittedRefunds($captureNumber, $vatSplittedRefunds)
    {
        $items = $this->getRefundItemList($vatSplittedRefunds);

        if (!count($items)) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('No valid refund itemd defined. They must contain a gross price at least.');
        }
        $RefundEntity = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\RefundEntity::class);
        $RefundEntity->setItems($items);
        $RefundEntity->setCaptureNumber($captureNumber);

        return $RefundEntity;
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
     * @param oxBasketItem[] $item
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
