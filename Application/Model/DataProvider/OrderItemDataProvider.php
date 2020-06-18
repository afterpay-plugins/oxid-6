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
class OrderItemDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Returns AfterPay order items from a given OXID basket.
     *
     * @param oxBasket $basket
     *
     * @return OrderItemEntity[]
     */
    public function getOrderItemList(\OxidEsales\Eshop\Application\Model\Basket $basket)
    {
        $list = [];

        $sumBrutto = 0;
        $sumNetto = 0;

        // Add articles

        foreach ($basket->getContents() as $item) {
            $oItem = $this->getOrderItem($item);
            $sumNetto += $oItem->getNetUnitPrice();
            $sumBrutto += $oItem->getGrossUnitPrice();
            $list[] = $oItem;
        }

        // Add delivery costs

        if ($basket->getDeliveryCost()->getBruttoPrice()) {
            $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);
            $orderItem->setProductId('ShippingFee');
            $orderItem->setDescription('Shipping/Versandkosten');
            $orderItem->setQuantity(1);
            $orderItem->setGrossUnitPrice($basket->getDeliveryCost()->getBruttoPrice());
            $orderItem->setNetUnitPrice($basket->getDeliveryCost()->getNettoPrice());
            $sumNetto += $basket->getDeliveryCost()->getNettoPrice();
            $sumBrutto += $basket->getDeliveryCost()->getBruttoPrice();
            $orderItem->setVatPercent($basket->getDeliveryCost()->getVat());
            $orderItem->setVatAmount($basket->getDeliveryCost()->getVatValue());

            // Set group ID if any item has a group id.
            foreach ($list as $oArticle) {
                if ($oArticle->hasGroupId() && $oArticle->getGroupId() !== null) {
                    $orderItem->setGroupId('0');
                    break;
                }
            }

            $list[] = $orderItem;
        }

        // Add vouchers

        if ($basket->getVoucherDiscount()) {
            $grossVaucher = 0 - round($basket->getVoucherDiscValue(), 2);
            $netVaucher = round($grossVaucher * ($sumNetto / $sumBrutto), 2);

            $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);
            $orderItem->setProductId('Vaucher');
            $orderItem->setDescription('Vaucher/Gutschein');
            $orderItem->setQuantity(1);
            $orderItem->setGrossUnitPrice($grossVaucher);
            $orderItem->setNetUnitPrice($netVaucher);
            $orderItem->setVatPercent(round(100 * (($sumBrutto / $sumNetto) - 1)));
            $orderItem->setVatAmount($grossVaucher - $netVaucher);

            // Set group ID if any item has a group id.
            foreach ($list as $oArticle) {
                if ($oArticle->hasGroupId() && $oArticle->getGroupId() !== null) {
                    $orderItem->setGroupId('0');
                    break;
                }
            }

            $list[] = $orderItem;
        }

        // Add discounts
        $fGrossDiscount = abs($basket->getBruttoSum()) - abs($basket->getDiscountedProductsBruttoPrice()) - abs($grossVaucher);

        if ($fGrossDiscount) {
            $fGrossDiscount = 0 - abs(round($fGrossDiscount, 2));
            $fNetDiscount = round($fGrossDiscount * ($sumNetto / $sumBrutto), 2);

            $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);
            $orderItem->setProductId('Discount');
            $orderItem->setDescription('Discount');
            $orderItem->setQuantity(1);
            $orderItem->setGrossUnitPrice($fGrossDiscount);
            $orderItem->setNetUnitPrice($fNetDiscount);
            $orderItem->setVatPercent(round(100 * (($fGrossDiscount / $fNetDiscount) - 1)));
            $orderItem->setVatAmount($fGrossDiscount - $fNetDiscount);

            // Set group ID if any item has a group id.
            foreach ($list as $oArticle) {
                if ($oArticle->hasGroupId() && $oArticle->getGroupId() !== null) {
                    $orderItem->setGroupId('0');
                    break;
                }
            }

            $list[] = $orderItem;
        }

        return $list;
    }

    /**
     * Transforms a basket item into an AfterPay order item.
     *
     * @param oxBasketItem $item
     *
     * @return OrderItemEntity
     */
    public function getOrderItem(\OxidEsales\Eshop\Application\Model\BasketItem $item)
    {
        $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);
        $orderItem->setProductId($item->getArticle()->oxarticles__oxid->value);
        $orderItem->setDescription($this->getItemDescription($item));
        $orderItem->setQuantity($item->getAmount());
        $orderItem->setGrossUnitPrice($item->getUnitPrice()->getBruttoPrice());
        $orderItem->setNetUnitPrice($item->getUnitPrice()->getNettoPrice());
        $orderItem->setUnitCode($item->getArticle()->getUnitName());
        $orderItem->setVatPercent($item->getUnitPrice()->getVat());
        $orderItem->setVatAmount($item->getUnitPrice()->getVatValue());
        $orderItem->setImageUrl($item->getArticle()->getPictureUrl());
        $orderItem->setProductUrl($item->getLink());

        if ($item->getArticle()->getAfterpayProductGroup()) {
            $orderItem->setGroupId($item->getArticle()->getAfterpayProductGroup());
        }

        return $orderItem;
    }

    /**
     * Returns the description of an basket item as title and select values.
     *
     * @param oxBasketItem $item
     *
     * @return string
     */
    protected function getItemDescription(\OxidEsales\Eshop\Application\Model\BasketItem $item)
    {
        $description = $item->getTitle();

        if (!empty($item->getChosenSelList())) {
            $description .= ' | ' . $item->getChosenSelList();
        }

        return $description;
    }
}
