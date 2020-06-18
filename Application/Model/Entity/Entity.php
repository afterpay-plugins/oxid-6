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

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Class arvatoAfterpayRequestEntity: Base class for all request entities.
 */
class Entity
{

    /**
     * Magic Method to get/set entity data, e.g. getCaptureNumber()
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed|void
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'get')) {
            $name = lcfirst(substr($name, 3));
            return $this->getData($name);
        } elseif (0 === strpos($name, 'set')) {
            if (!array_key_exists(0, $arguments)) {
                throw new \OxidEsales\Eshop\Core\Exception\StandardException("Calling Setter $name without value argument");
            }
            $name = lcfirst(substr($name, 3));
            return $this->setData($name, $arguments[0]);
        } elseif (0 === strpos($name, 'has')) {
            $name = lcfirst(substr($name, 3));
            return array_key_exists($name, $this->_data);
        }
    }

    /**
     * Data container.
     *
     * @var array
     */
    protected $_data = [];

    /**
     * List of errors that occured while validating the data.
     *
     * @var string[]
     */
    protected $_validationErrors = [];

    /**
     * List of returned business errors
     *
     * @var string[]
     */
    protected $_businessErrors = [];

    /**
     * Message intended for the customer
     *
     * @var string
     */
    protected $_customerFacingMessage = '';

    /**
     * Sets the data for a special key.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function setData($key, $value)
    {
        // We wont save empty strings or null, but we explicitly need the number 0.
        if (isset($value) && '' !== $value) {
            $this->_data[$key] = $value;
        } else {
            unset($this->_data[$key]);
        }
    }

    /**
     * Gets the data for a special key.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getData($key)
    {
        return $this->_data[$key];
    }

    /**
     * Adds an item to a data array.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function addItem($key, $value)
    {
        if (is_array($this->_data[$key])) {
            $this->_data[$key][] = $value;
        } elseif (is_null($this->_data[$key])) {
            $this->_data[$key] = array($value);
        } else {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Property ' . $key . 'is not an array.');
        }
    }

    /**
     * Exports all data in a plain data object.
     *
     * @param string $envelopeObjectName Allows to encapsulate the whole data into an envelope object,
     *      e.g. put paymenttype withhin paymentInfo-Envelope
     *
     * @return object
     */
    public function exportData($envelopeObjectName = null)
    {

        $exportData = $this->_data;
        array_walk_recursive(
            $exportData,
            function (&$item, $key) {
                if ($item instanceof Entity) {
                    $item = $item->exportData();
                }
            }
        );

        if ($envelopeObjectName) {
            $envelope = new \stdClass();
            $envelope->$envelopeObjectName = $exportData;
            $exportData = $envelope;
        }

        return (object)$exportData;
    }

    /**
     * @param string[] $businessErrors
     */
    public function setErrors(array $businessErrors)
    {
        $this->_businessErrors = $businessErrors;
    }

    /**
     * @return string[] businessErrors
     */
    public function getErrors()
    {
        return $this->_businessErrors;
    }

    /**
     * @return string
     */
    public function getCustomerFacingMessage()
    {
        return $this->_customerFacingMessage;
    }

    /**
     * @param string $customerFacingMessage
     *
     * @return mixed
     */
    public function setCustomerFacingMessage($customerFacingMessage)
    {
        return $this->_customerFacingMessage = $customerFacingMessage;
    }
}
