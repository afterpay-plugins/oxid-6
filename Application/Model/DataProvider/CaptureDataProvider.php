<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity;
use OxidEsales\Eshop\Application\Model\Order;

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
     * @param Order $order
     *
     * @param array|null $orderItems
     *
     * @return CaptureEntity
     */
    public function getDataObject(\OxidEsales\Eshop\Application\Model\Order $order, array $orderItems = null)
    {
        $orderDetails = new \stdClass();
        foreach ($orderItems as $k => $v) {
            unset($orderItems[$k]->oxArticle);
        }

        if (!count($orderItems)) {
            $orderDetails->totalNetAmount = (float)$order->oxorder__oxtotalnetsum->value;
            $orderDetails->totalGrossAmount = (float)$order->oxorder__oxtotalbrutsum->value;
        } else {
            $orderDetails->items = array_values($orderItems); //remove any array keys
            $orderDetails->totalNetAmount = 0;
            $orderDetails->totalGrossAmount = 0;
            foreach ($orderItems as $objOrderItem) {
                $orderDetails->totalNetAmount += (float)$objOrderItem->netUnitPrice * $objOrderItem->quantity;
                $orderDetails->totalGrossAmount += (float)$objOrderItem->grossUnitPrice * $objOrderItem->quantity;
            }
        }

        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity::class);
        $dataObject->setOrderDetails($orderDetails);
        return $dataObject;
    }
}
