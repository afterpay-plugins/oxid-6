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

namespace Arvato\AfterpayModule\Application\Model;

/**
 * Class AfterpayOrder
 *
 *  Naming of the "*Order"-Classes:
 *    - ArvatoAfterpayOxOrder: Exctension of oxOrder - model
 *    - OrderController: Exctension of order - view
 *    - AfterpayOrder: New model as seen in db table afterpayorder <-- THIS FILE
 */
class AfterpayOrder extends \OxidEsales\Eshop\Core\Model\BaseModel
{
    public const AFTERPAYSTATUS_AUTHORIZED = 'authorized';
    public const AFTERPAYSTATUS_AUTHORIZATIONVOIDED = 'authorizationvoided';
    public const AFTERPAYSTATUS_CAPTURED = 'captured';
    public const AFTERPAYSTATUS_REFUNDED = 'refunded';

    /**
     * @var string Name of current class
     */
    protected $_sClassName = 'Arvato\AfterpayModule\Application\Model\AfterpayOrder';

    /**
     * @var string Database Table to persist entities
     */
    protected $_sCoreTable = 'arvatoafterpayafterpayorder';

    /**
     * @var oxOrder The oxorder to bind this class to. (1:1 similar to orartextends)
     */
    protected $_sOxOrder = null;

    /**
     * Class constructor
     *
     * @param oxOrder The oxorder to bind this class to. (1:1 similar to orartextends)
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $oxOrder)
    {
        $this->_sOxOrder = $oxOrder;
        $this->setId($oxOrder->getId());
        parent::__construct();
        $this->init();
        return;
    }

    /**
     * Fill this order-bound entity by data out of the session
     * since the order is not yet available during finalizeOrder()
     *
     * @param oxSession $session
     */
    public function fillBySession(\OxidEsales\Eshop\Core\Session $session)
    {
        $this->arvatoafterpayafterpayorder__apreservationid = new \OxidEsales\Eshop\Core\Field(
            $session->getVariable('arvatoAfterpayReservationId'),
            \OxidEsales\Eshop\Core\Field::T_RAW
        );
        $this->arvatoafterpayafterpayorder__apcheckoutid = new \OxidEsales\Eshop\Core\Field(
            $session->getVariable('arvatoAfterpayCheckoutId'),
            \OxidEsales\Eshop\Core\Field::T_RAW
        );
        $this->arvatoafterpayafterpayorder__apstatus = new \OxidEsales\Eshop\Core\Field(
            self::AFTERPAYSTATUS_AUTHORIZED,
            \OxidEsales\Eshop\Core\Field::T_RAW
        );
        $this->arvatoafterpayafterpayorder__apusedapikey = new \OxidEsales\Eshop\Core\Field(
            $session->getVariable('arvatoAfterpayApiKey'),
            \OxidEsales\Eshop\Core\Field::T_RAW
        );
    }

    /**
     * Sets status
     *
     * @param string $status see class constants
     * @param string $eventNo e.g. CaptureNo
     */
    public function setStatus($status, $eventNo = null)
    {
        $reflection = new \ReflectionClass(__CLASS__);
        $classConstants = $reflection->getConstants();

        if (in_array($status, $classConstants)) {
            $this->arvatoafterpayafterpayorder__apstatus = new \OxidEsales\Eshop\Core\Field($status, \OxidEsales\Eshop\Core\Field::T_RAW);

            if (self::AFTERPAYSTATUS_CAPTURED == $status) {
                $this->arvatoafterpayafterpayorder__apcaptureno = new \OxidEsales\Eshop\Core\Field($eventNo, \OxidEsales\Eshop\Core\Field::T_RAW);
                $this->arvatoafterpayafterpayorder__apcapturetimestamp = new \OxidEsales\Eshop\Core\Field(date("Y-m-d H:i:s"), \OxidEsales\Eshop\Core\Field::T_RAW);
            }
        } else {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Illegal status ' . serialize($status) . ', choose among the class constants');
        }
    }

    /**
     * Gets status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->arvatoafterpayafterpayorder__apstatus->value;
    }

    /**
     * Gets last capture number
     *
     * @return string $captureNo
     */
    public function getCaptureNo()
    {
        return $this->arvatoafterpayafterpayorder__apcaptureno->value;
    }

    /**
     * Gets used api key
     * @codeCoverageIgnore
     * @return string $AfterpayOrder__apusedapikey
     */
    public function getUsedApiKey()
    {
        return $this->arvatoafterpayafterpayorder__apusedapikey->value;
    }

    /**
     * @return oxOrder
     * @codeCoverageIgnore
     */
    public function getOxOrder()
    {
        return $this->_sOxOrder;
    }
}
