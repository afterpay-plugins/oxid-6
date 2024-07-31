<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\AfterpayOrder;
use Arvato\AfterpayModule\Application\Model\Entity\CaptureResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CaptureShippingResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\Entity;
use Arvato\AfterpayModule\Application\Model\Entity\ResponseMessageEntity;
use Arvato\AfterpayModule\Core\Exception\CurlException;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Session;
use stdClass;

/**
 * Class Service
 */
class Service
{
    /**
     * @var CaptureResponseEntity result entity.
     */
    protected $_entity;

    /**
     * @return CaptureShippingResponseEntity
     */
    protected function getEntity()
    {
        return $this->_entity;
    }

    /**
     * @var AfterpayOrder
     */
    protected $_afterpayOrder;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var Language Current language.
     */
    protected $_lang;

    /**
     * @var Session Current session.
     */
    protected $_session;

    /**
     * @var int Last error code, eg. OrderController::ARVATO_ORDER_STATE_CHECKADDRESS
     */
    protected $_iLastErrorNo = 0;

    /**
     * Resturns the error messages from a request.
     *
     * @return string
     */
    public function getErrorMessages()
    {
        if ($this->getEntity() && $this->getEntity()->getCustomerFacingMessage()) {
            return $this->getEntity()->getCustomerFacingMessage();
        }

        $errorMessages = [];

        if ($this->getEntity() && is_array($this->getEntity()->getErrors()) && count($this->getEntity()->getErrors())) {
            $businessErrors = $this->getEntity()->getErrors();

            foreach ($businessErrors as $businessError) {
                if (is_array($businessError)) {
                    $businessError = reset($businessError);
                }

                if ($businessError instanceof ResponseMessageEntity) {
                    $errorMessages[] = $businessError->exportData()->customerFacingMessage ?: $businessError->exportData()->message;
                } elseif ($businessError instanceof stdClass) {
                    $errorMessages[] = $businessError->customerFacingMessage ?: $businessError->message;
                }
            }
        }

        return join('; ', $errorMessages);
    }

    /**
     * @return int
     */
    public function getLastErrorNo()
    {
        if ($this->getEntity() && $this->getEntity()->getCustomerFacingMessage()) {
            return \OxidEsales\Eshop\Application\Model\Order::ORDER_STATE_PAYMENTERROR;
        }

        return $this->_iLastErrorNo;
    }

    /**
     * Gets an entity from the service result.
     *
     * @param stdClass|array $response
     *
     * @return Entity
     * @throws CurlException
     */
    protected function parseResponse($response)
    {
        try {
            $base = $this->getBaseClassName();
            if (is_array($response)) {
                $entity = oxNew('\\Arvato\\AfterpayModule\\Application\\Model\\Entity\\' . $base . 'ResponseEntity');
                $messages = [];
                foreach ($response as $item) {
                    $messages[] = oxNew(\Arvato\AfterpayModule\Application\Model\Parser\ResponseMessageParser::class)->parse($item);
                }
                $entity->setErrors($messages);
            } elseif (is_object($response)) {
                $entity = oxNew('\\Arvato\\AfterpayModule\\Application\\Model\\Parser\\' . $base . 'ResponseParser')->parse($response);
            } else {
                throw new \Arvato\AfterpayModule\Core\Exception\CurlException('Cannot parse non-StdClass response ' . serialize($response));
            }
            return $entity;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getBaseClassName()
    {
        $className = str_replace(__NAMESPACE__ . '\\', '', get_class($this));
        if (0 === strpos($className, 'Mock_')) {
            // Unit Test helper: Turn mocked Mock_someClass_a1b2c3d into someClass
            $className = substr($className, 5);
            $className = substr($className, 0, -9);
        }

        $className = str_replace('Service', '', $className);

        return $className;
    }
}
