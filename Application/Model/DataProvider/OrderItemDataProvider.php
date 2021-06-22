<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity;
use oxArticleInputException;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\BasketItem;
use OxidEsales\Eshop\Core\Registry;
use oxNoArticleException;

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
     * @param Basket $basket
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
            $objItem = $this->getOrderItem($item);
            $sumNetto += $objItem->getNetUnitPrice();
            $sumBrutto += $objItem->getGrossUnitPrice();
            $list[] = $objItem;
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
            foreach ($list as $article) {
                if ($article->hasGroupId() && $article->getGroupId() !== null) {
                    $orderItem->setGroupId('0');
                    break;
                }
            }

            $list[] = $orderItem;
        }

        // Add vouchers

        if ($basket->getVoucherDiscount()) {
            $grossVoucher = 0 - round($basket->getVoucherDiscValue(), 2);
            $netVoucher = round($grossVoucher * ($sumNetto / $sumBrutto), 2);

            $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);
            $orderItem->setProductId('Voucher');
            $orderItem->setDescription('Voucher/Gutschein');
            $orderItem->setQuantity(1);
            $orderItem->setGrossUnitPrice($grossVoucher);
            $orderItem->setNetUnitPrice($netVoucher);
            $orderItem->setVatPercent(round(100 * (($sumBrutto / $sumNetto) - 1)));
            $orderItem->setVatAmount($grossVoucher - $netVoucher);

            // Set group ID if any item has a group id.
            foreach ($list as $article) {
                if ($article->hasGroupId() && $article->getGroupId() !== null) {
                    $orderItem->setGroupId('0');
                    break;
                }
            }

            $list[] = $orderItem;
        }

        // Add discounts
        $grossDiscount = abs($basket->getBruttoSum()) - abs($basket->getDiscountedProductsBruttoPrice()) - abs($grossVoucher);

        if ($grossDiscount) {
            $grossDiscount = 0 - abs(round($grossDiscount, 2));
            if ($grossDiscount) {

                $netDiscount = round($grossDiscount * ($sumNetto / $sumBrutto), 2);

                $orderItem = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderItemEntity::class);
                $orderItem->setProductId('Discount');
                $orderItem->setDescription('Discount');
                $orderItem->setQuantity(1);
                $orderItem->setGrossUnitPrice($grossDiscount);
                $orderItem->setNetUnitPrice($netDiscount);
                $orderItem->setVatPercent(round(100 * (($grossDiscount / $netDiscount) - 1)));
                $orderItem->setVatAmount($grossDiscount - $netDiscount);

                // Set group ID if any item has a group id.
                foreach ($list as $article) {
                    if ($article->hasGroupId() && $article->getGroupId() !== null) {
                        $orderItem->setGroupId('0');
                        break;
                    }
                }

                $list[] = $orderItem;
            }
        }

        return $list;
    }

    /**
     * Transforms a basket item into an AfterPay order item.
     *
     * @param BasketItem $item
     *
     * @return OrderItemEntity
     */
    public function getOrderItem(BasketItem $item)
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

        return $orderItem;
    }

    /**
     * Returns the description of an basket item as title and select values.
     *
     * @param BasketItem $item
     *
     * @throws oxArticleInputException
     * @throws oxNoArticleException
     * @return string
     */
    protected function getItemDescription(BasketItem $item)
    {
        $article = $item->getArticle();
        // Using article.oxtitle here since the basket item title already contains the oxvarselect
        $description = $article->getFieldData('oxtitle');

        $manufacturerSetting = Registry::getConfig()->getConfigParam('arvatoAfterpayManufacturerInDescription');
        if ($manufacturerSetting === 'manufacturer' && ($manufacturer = $article->getManufacturer())) {
            $description = $manufacturer->getTitle() . ' ' . $description;
        } elseif ($manufacturerSetting === 'vendor' && ($vendor = $article->getVendor())) {
            $description = $vendor->getTitle() . ' ' . $description;
        }

        $addVarSelect = Registry::getConfig()->getConfigParam('arvatoAfterpayVariantInDescription') === 'yes';
        if ($addVarSelect && $article->getFieldData('oxvarselect')) {
            $description .= ' ' . $article->getFieldData('oxvarselect');
        }

        if (!empty($item->getChosenSelList())) {
            $description .= ' | ' . $item->getChosenSelList();
        }

        return $description;
    }
}
