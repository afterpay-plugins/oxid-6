<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity;

/**
 * Class CaptureShippingDataProvider: Data provider for capture shipping
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class CaptureShippingDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the data object for an AfterPay capture request.
     *
     * @param string $trackingId as provided by the carrier company
     * @param string $shippingCompany e.g. dhl, ups, dpd
     * @param string $type Shipment / Return
     *
     * @return CaptureEntity
     */
    public function getDataObject($trackingId, $shippingCompany, $type)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingEntity::class);
        $dataObject->setTrackingId($trackingId);
        $dataObject->setShippingCompany($shippingCompany);
        $dataObject->setType($type);
        return $dataObject;
    }
}
