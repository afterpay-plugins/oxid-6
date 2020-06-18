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

namespace Arvato\AfterpayModule\Application\Model\Parser;

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
