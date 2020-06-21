<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity;
use stdClass;

/**
 * Class CaptureShippingResponseParser: Parser for the capture shipping response.
 */
class CaptureShippingResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    /**
     * Parses a standard object into a entity.
     *
     * @param stdClass $object
     *
     * @return CaptureShippingResponseEntity
     */
    public function parse(\stdClass $object)
    {
        $responseMessage = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity::class);
        if (isset($object->shippingNumber)) {
            $responseMessage->setShippingNumber($object->shippingNumber);
        }
        if (isset($object->message)) {
            $responseMessage->setErrors([$object->message]);
        }
        return $responseMessage;
    }
}
