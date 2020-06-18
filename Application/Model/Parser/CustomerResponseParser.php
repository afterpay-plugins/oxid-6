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
 * Class CustomerResponseParser: Parser for the customer response.
 *
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class CustomerResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    /**
     * Parses a standard object into a entity.
     *
     * @param stdClass $object
     * @return CustomerResponseEntity
     */
    public function parse(\stdClass $object)
    {
        $responseMessage = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CustomerResponseEntity::class);

        $responseMessage->setCustomerNumber($object->customerNumber);
        $responseMessage->setFirstName($object->firstName);
        $responseMessage->setLastName($object->lastName);

        if (is_array($object->addressList)) {
            foreach ($object->addressList as $address) {
                $responseMessage->addAddress(oxNew(\Arvato\AfterpayModule\Application\Model\Parser\AddressParser::class)->parse($address));
            }
        }

        return $responseMessage;
    }
}
