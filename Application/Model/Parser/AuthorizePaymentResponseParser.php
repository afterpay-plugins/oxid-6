<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentResponseEntity;
use stdClass;

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
