<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class OrderEntity: Entity for order data.
 */
class OrderEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{
    /**
     * Constants for currency property.
     */
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_NOK = 'NOK';
    const CURRENCY_SEK = 'SEK';
    const CURRENCY_DKK = 'DKK';
    const CURRENCY_CHF = 'CHF';

    /**
     * Getter for order number property.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->getData('number');
    }

    /**
     * Setter for order number property.
     *
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->setData('number', $number);
    }

    /**
     * Getter for total gross amount property.
     *
     * @return float
     */
    public function getTotalGrossAmount()
    {
        return $this->getData('totalGrossAmount');
    }

    /**
     * Setter for total gross amount property.
     *
     * @param float $totalGrossAmount
     */
    public function setTotalGrossAmount($totalGrossAmount)
    {
        $this->setData('totalGrossAmount', $totalGrossAmount);
    }

    /**
     * Getter for currency property.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getData('currency');
    }

    /**
     * Setter for currency property.
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->setData('currency', $currency);
    }

    /**
     * Getter for items property.
     *
     * @return OrderItemEntity[]
     */
    public function getItems()
    {
        return $this->getData('items');
    }

    /**
     * Setter for items property.
     *
     * @param OrderItemEntity[] $items
     */
    public function setItems($items)
    {
        $this->setData('items', $items);
    }

    /**
     * Getter for total net amount property.
     *
     * @return float
     */
    public function getTotalNetAmount()
    {
        return $this->getData('totalNetAmount');
    }

    /**
     * Setter for total net amount property.
     *
     * @param float $totalNetAmount
     */
    public function setTotalNetAmount($totalNetAmount)
    {
        $this->setData('totalNetAmount', $totalNetAmount);
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
     * Getter for google analytics user id property.
     *
     * @return string
     */
    public function getGoogleAnalyticsUserId()
    {
        return $this->getData('googleAnalyticsUserId');
    }

    /**
     * Setter for google analytics user id property.
     *
     * @param string $googleAnalyticsUserId
     */
    public function setGoogleAnalyticsUserId($googleAnalyticsUserId)
    {
        $this->setData('googleAnalyticsUserId', $googleAnalyticsUserId);
    }

    /**
     * Getter for google analytics client id property.
     *
     * @return string
     */
    public function getGoogleAnalyticsClientId()
    {
        return $this->getData('googleAnalyticsClientId');
    }

    /**
     * Setter for google analytics client id property.
     *
     * @param string $googleAnalyticsClientId
     */
    public function setGoogleAnalyticsClientId($googleAnalyticsClientId)
    {
        $this->setData('googleAnalyticsClientId', $googleAnalyticsClientId);
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
}
