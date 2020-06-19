<?php

/**
 *
*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class OrderItemEntity: Entity for order item data.
 */
class OrderItemEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Constants for vat category property.
     */
    const VAT_CATEGORY_HIGH = 'HighCategory';
    const VAT_CATEGORY_LOW = 'LowCategory';
    const VAT_CATEGORY_NULL = 'NullCategory';
    const VAT_CATEGORY_NO = 'NoCategory';
    const VAT_CATEGORY_MIDDLE = 'MiddleCategory';
    const VAT_CATEGORY_OTHER = 'OtherCategory';

    /**
     * Getter for product id property.
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->getData('productId');
    }

    /**
     * Setter for product id property.
     *
     * @param string $productId
     */
    public function setProductId($productId)
    {
        $this->setData('productId', $productId);
    }

    /**
     * Getter for description property.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * Setter for description property.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->setData('description', $description);
    }

    /**
     * Getter for gross unit price property.
     *
     * @return float
     */
    public function getGrossUnitPrice()
    {
        return $this->getData('grossUnitPrice');
    }

    /**
     * Setter for gross unit price property.
     *
     * @param float $grossUnitPrice
     */
    public function setGrossUnitPrice($grossUnitPrice)
    {
        $this->setData('grossUnitPrice', $grossUnitPrice);
    }

    /**
     * Getter for quantity property.
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->getData('quantity');
    }

    /**
     * Setter for quantity property.
     *
     * @param float $quantity
     */
    public function setQuantity($quantity)
    {
        $this->setData('quantity', $quantity);
    }

    /**
     * Getter for group id property.
     *
     * @return string
     */
    public function getGroupId()
    {
        return $this->getData('groupId');
    }

    /**
     * Setter for group id property.
     *
     * @param string $groupId
     */
    public function setGroupId($groupId)
    {
        $this->setData('groupId', $groupId);
    }

    /**
     * Getter for net unit price property.
     *
     * @return float
     */
    public function getNetUnitPrice()
    {
        return $this->getData('netUnitPrice');
    }

    /**
     * Setter for net unit price property.
     *
     * @param float $netUnitPrice
     */
    public function setNetUnitPrice($netUnitPrice)
    {
        $this->setData('netUnitPrice', $netUnitPrice);
    }

    /**
     * Getter for unit code property.
     *
     * @return string
     */
    public function getUnitCode()
    {
        return $this->getData('unitCode');
    }

    /**
     * Setter for unit code property.
     *
     * @param string $unitCode
     */
    public function setUnitCode($unitCode)
    {
        $this->setData('unitCode', $unitCode);
    }

    /**
     * Getter for vat category property.
     *
     * @return string
     */
    public function getVatCategory()
    {
        return $this->getData('vatCategory');
    }

    /**
     * Setter for vat category property.
     *
     * @param string $vatCategory
     */
    public function setVatCategory($vatCategory)
    {
        $this->setData('vatCategory', $vatCategory);
    }

    /**
     * Getter for vat percent property.
     *
     * @return float
     */
    public function getVatPercent()
    {
        return $this->getData('vatPercent');
    }

    /**
     * Setter for vat percent property.
     *
     * @param float $vatPercent
     */
    public function setVatPercent($vatPercent)
    {
        $this->setData('vatPercent', $vatPercent);
    }

    /**
     * Getter for vat amount property.
     *
     * @return float
     */
    public function getVatAmount()
    {
        return $this->getData('vatAmount');
    }

    /**
     * Setter for vat amount property.
     *
     * @param float $vatAmount
     */
    public function setVatAmount($vatAmount)
    {
        $this->setData('vatAmount', $vatAmount);
    }

    /**
     * Getter for image url property.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getData('imageUrl');
    }

    /**
     * Setter for image url property.
     *
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->setData('imageUrl', $imageUrl);
    }

    /**
     * Getter for google product category id property.
     *
     * @return int
     */
    public function getGoogleProductCategoryId()
    {
        return $this->getData('googleProductCategoryId');
    }

    /**
     * Setter for google product category id property.
     *
     * @param int $googleProductCategoryId
     */
    public function setGoogleProductCategoryId($googleProductCategoryId)
    {
        $this->setData('googleProductCategoryId', $googleProductCategoryId);
    }

    /**
     * Getter for google product category property.
     *
     * @return string
     */
    public function getGoogleProductCategory()
    {
        return $this->getData('googleProductCategory');
    }

    /**
     * Setter for google product category property.
     *
     * @param string $googleProductCategory
     */
    public function setGoogleProductCategory($googleProductCategory)
    {
        $this->setData('googleProductCategory', $googleProductCategory);
    }

    /**
     * Getter for merchant product type property.
     *
     * @return string
     */
    public function getMerchantProductType()
    {
        return $this->getData('merchantProductType');
    }

    /**
     * Setter for merchant product type property.
     *
     * @param string $merchantProductType
     */
    public function setMerchantProductType($merchantProductType)
    {
        $this->setData('merchantProductType', $merchantProductType);
    }

    /**
     * Getter for line number id property.
     *
     * @return int
     */
    public function getLineNumber()
    {
        return $this->getData('lineNumber');
    }

    /**
     * Setter for line number id property.
     *
     * @param int $lineNumber
     */
    public function setLineNumber($lineNumber)
    {
        $this->setData('lineNumber', $lineNumber);
    }

    /**
     * Getter for discount amount property.
     *
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->getData('discountAmount');
    }

    /**
     * Setter for discount amount property.
     *
     * @param float $discountAmount
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->setData('discountAmount', $discountAmount);
    }

    /**
     * Getter for product url property.
     *
     * @return string
     */
    public function getProductUrl()
    {
        return $this->getData('productUrl');
    }

    /**
     * Setter for product url property.
     *
     * @param string $productUrl
     */
    public function setProductUrl($productUrl)
    {
        $this->setData('productUrl', $productUrl);
    }

    /**
     * Getter for market place seller id property.
     *
     * @return string
     */
    public function getMarketPlaceSellerId()
    {
        return $this->getData('marketPlaceSellerId');
    }

    /**
     * Setter for market place seller id property.
     *
     * @param string $marketPlaceSellerId
     */
    public function setMarketPlaceSellerId($marketPlaceSellerId)
    {
        $this->setData('marketPlaceSellerId', $marketPlaceSellerId);
    }
}
