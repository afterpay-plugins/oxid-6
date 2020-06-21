<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\Entity;
use Arvato\AfterpayModule\Application\Model\Entity\ResponseMessageEntity;
use stdClass;

/**
 * Class Parser: Parser for the customer response.
 */
abstract class Parser
{

    protected $fields = [];

    /**
     * Parses api-provided json-response into request-specific response object
     *
     * @param stdClass $object
     *
     * @return Entity
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    public function parse(\stdClass $object)
    {

        if (!count($this->fields)) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Specify list of fields');
        }

        $responseEntityClassname = $this->getResponseEntityClassname();

        /**
         * @var ResponseMessageEntity $responseMessage
         */
        $responseMessage = oxNew($responseEntityClassname);

        foreach ($this->fields as $field) {
            $setter = "set" . ucfirst($field);
            $responseMessage->$setter($object->$field);
        }

        if (isset($object->message)) {
            $responseMessage->setErrors([$object->message]);
        }

        if (isset($object->riskCheckMessages)) {
            $responseMessage->setErrors([$object->riskCheckMessages]);

            foreach ($object->riskCheckMessages as $riskCheckMessage) {
                if ($riskCheckMessage->customerFacingMessage) {
                    $responseMessage->setCustomerFacingMessage($riskCheckMessage->customerFacingMessage);
                }
            }
        }

        return $responseMessage;
    }


    /**
     * Returns Entity Classname,
     * e.g. turns ResponseMessageParser into ResponseMessageEntity
     *
     * @return string
     */
    protected function getResponseEntityClassname()
    {
        $parserClass = get_class($this);
        return str_replace('Parser', 'Entity', $parserClass);
    }
}
