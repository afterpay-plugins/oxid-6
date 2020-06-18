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
 * Class AddressParser: Parser for address entities.
 */
class AddressParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    /**
     * Parses a standard object into a entity.
     *
     * @param stdClass $object
     * @return AddressEntity
     */
    public function parse(\stdClass $object)
    {
        $responseMessage = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AddressEntity::class);

        $responseMessage->setCountryCode($object->countryCode);
        $responseMessage->setPostalCode($object->postalCode);
        $responseMessage->setStreet($object->street);
        $responseMessage->setStreetNumber($object->streetNumber);
        $responseMessage->setStreetNumberAdditional($object->streetNumberAdditional);
        $responseMessage->setPostalPlace($object->postalPlace);
        $responseMessage->setCareOf($object->careOf);
        return $responseMessage;
    }
}
