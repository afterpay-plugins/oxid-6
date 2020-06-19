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

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\ResponseMessageEntity;
use stdClass;

/**
 * Class ResponseMessageParser: Parser for the response messages.
 */
class ResponseMessageParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    /**
     * Parses a standard object into a entity.
     *
     * @param stdClass $object
     * @return ResponseMessageEntity
     */
    public function parse(\stdClass $object)
    {
        $responseMessage = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\ResponseMessageEntity::class);

        $responseMessage->setType($object->type);
        $responseMessage->setCode($object->code);
        $responseMessage->setMessage($object->message);
        $responseMessage->setCustomerFacingMessage($object->customerFacingMessage);
        $responseMessage->setActionCode($object->actionCode);
        $responseMessage->setFieldReference($object->fieldReference);

        return $responseMessage;
    }
}
