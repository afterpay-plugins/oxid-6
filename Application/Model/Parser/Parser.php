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
 * Class Parser: Parser for the customer response.
 */
abstract class Parser
{

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

        if (!count($this->aFields)) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Specify list of fields');
        }

        $responseEntityClassname = $this->getResponseEntityClassname();

        /**
         * @var ResponseMessageEntity $responseMessage
         */
        $responseMessage = oxNew($responseEntityClassname);

        foreach ($this->aFields as $sField) {
            $sSetter = "set" . ucfirst($sField);
            $responseMessage->$sSetter($object->$sField);
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

    protected $aFields = [];

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
