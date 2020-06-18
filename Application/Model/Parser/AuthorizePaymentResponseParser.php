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
 * Class CustomerResponseParser: Parser for the authorize payment response.
 */
class AuthorizePaymentResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{
    /**
     * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
     * (only getters and setters), can be excluded from test coverage:
     *
     * @codeCoverageIgnore
     *
     * @param stdClass $object
     *
     * @return AuthorizePaymentResponseEntity
     */
    public function parse(\stdClass $object)
    {

        /** @var AuthorizePaymentResponseEntity $responseMessage */
        $responseMessage = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentResponseEntity::class);

        if (isset($object->outcome)) {
            $responseMessage->setOutcome($object->outcome);
        }

        if (isset($object->customer)) {
            $responseMessage->setCustomer(oxNew(\Arvato\AfterpayModule\Application\Model\Parser\CustomerResponseParser::class)->parse($object->customer));
        }

        if (isset($object->deliveryCustomer)) {
            $responseMessage->setDeliveryCustomer(oxNew(\Arvato\AfterpayModule\Application\Model\Parser\CustomerResponseParser::class)->parse($object->deliveryCustomer));
        }

        if (isset($object->reservationId)) {
            $responseMessage->setReservationId($object->reservationId);
        }

        if (isset($object->checkoutId)) {
            $responseMessage->setCheckoutId($object->checkoutId);
        }

        if (is_array($object->riskCheckMessages)) {
            foreach ($object->riskCheckMessages as $riskCheckMessage) {
                $responseMessage->addAddress(oxNew(\Arvato\AfterpayModule\Application\Model\Parser\ResponseMessageParser::class)->parse($riskCheckMessage));
                if ($riskCheckMessage->customerFacingMessage) {
                    $responseMessage->setCustomerFacingMessage($riskCheckMessage->customerFacingMessage);
                }
            }
        }

        return $responseMessage;
    }
}
