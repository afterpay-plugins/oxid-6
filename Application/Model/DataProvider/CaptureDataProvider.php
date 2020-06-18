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
 * Class CaptureDataProvider: Data provider for capture payment data.
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class CaptureDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the data object for an AfterPay capture request.
     *
     * @param oxOrder $oxOrder
     *
     * @param array|null $aOrderItems
     *
     * @return CaptureEntity
     */
    public function getDataObject(\OxidEsales\Eshop\Application\Model\Order $oxOrder, array $aOrderItems = null)
    {
        $orderDetails = new \stdClass();
        foreach ($aOrderItems as $k => $v) {
            unset($aOrderItems[$k]->oxArticle);
        }

        if (!count($aOrderItems)) {
            $orderDetails->totalNetAmount = (float)$oxOrder->oxorder__oxtotalnetsum->value;
            $orderDetails->totalGrossAmount = (float)$oxOrder->oxorder__oxtotalbrutsum->value;
        } else {
            $orderDetails->items = array_values($aOrderItems); //remove any array keys
            $orderDetails->totalNetAmount = 0;
            $orderDetails->totalGrossAmount = 0;
            foreach ($aOrderItems as $oOrderItem) {
                $orderDetails->totalNetAmount += (float)$oOrderItem->netUnitPrice * $oOrderItem->quantity;
                $orderDetails->totalGrossAmount += (float)$oOrderItem->grossUnitPrice * $oOrderItem->quantity;
            }
        }

        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity::class);
        $dataObject->setOrderDetails($orderDetails);
        return $dataObject;
    }
}
