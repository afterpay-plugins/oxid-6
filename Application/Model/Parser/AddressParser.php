<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\AddressEntity;
use stdClass;

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
