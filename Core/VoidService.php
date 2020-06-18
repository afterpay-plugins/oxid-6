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

namespace Arvato\AfterpayModule\Core;

/**
 * Class VoidService: Service for voiding autorized payments with AfterPay.
 */
class VoidService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * Standard constructor.
     *
     * @param oxOrder $oxOrder
     *
     * @internal param oxSession $session
     * @internal param oxLang $lang
     *
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    public function __construct(\OxidEsales\Eshop\Application\Model\Order $oxOrder)
    {
        $this->_oxOrder = $oxOrder;
        $this->_afterpayOrder = $oxOrder->getAfterpayOrder();
    }

    /**
     * Performs the void call.
     *
     * @param $sRecordedApiKey
     *
     * @param array|null $aOrderItems
     *
     * @return VoidResponseEntity
     */
    public function void($sRecordedApiKey, array $aOrderItems = null)
    {
        $response = $this->executeRequestFromOrderData($sRecordedApiKey, $aOrderItems);
        $this->_entity = $this->parseResponse($response);

        if (
            is_numeric($this->getEntity()->getTotalAuthorizedAmount())
            && is_numeric($this->getEntity()->getTotalCapturedAmount())
        ) {
            $this->_afterpayOrder->setStatus(\Arvato\AfterpayModule\Application\Model\AfterpayOrder::AFTERPAYSTATUS_AUTHORIZATIONVOIDED);
            $this->_afterpayOrder->save();
        }

        return $this->getEntity();
    }

    /**
     * @param $sRecordedApiKey
     * @param array|null $aOrderItems
     *
     * @return stdClass|stdClass[]
     *
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    protected function executeRequestFromOrderData($sRecordedApiKey, array $aOrderItems = null)
    {
        $data = $this->getVoidDataForApi($aOrderItems);
        $client = $this->getVoidClientForApi($sRecordedApiKey);
        return $client->execute($data);
    }

    /////////////////////////////////////////////////////
    /// //UNIT TEST HELPER
    /// @CodeCoverageIgnoreStart

    /**
     * Elevating visibility
     *
     * @param $sRecordedApiKey
     *
     * @return stdClass|stdClass[]
     * @codeCoverageIgnore Deliberately uncovered since only setter & getter
     */
    public function testexecuteRequestFromOrderData($sRecordedApiKey)
    {
        return $this->executeRequestFromOrderData($sRecordedApiKey);
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param array|null $aOrderItems
     *
     * @return stdClass
     */
    protected function getVoidDataForApi(array $aOrderItems = null)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\VoidDataProvider::class)->getDataObject($this->_oxOrder, $aOrderItems)->exportData();
    }

    /**
     * @codeCoverageIgnore Deliberately uncovered unit test helper
     *
     * @param $sRecordedApiKey
     *
     * @return WebServiceClient
     */
    protected function getVoidClientForApi($sRecordedApiKey)
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getVoidClient(
            $this->_oxOrder->oxorder__oxordernr->value,
            $sRecordedApiKey
        );
    }
}
