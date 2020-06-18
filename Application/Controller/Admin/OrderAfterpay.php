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

namespace Arvato\AfterpayModule\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * Order class wrapper for Afterpay module
 */
class OrderAfterpay extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Executes parent method parent::render(), creates oxOrder object,
     * passes it's data to Smarty engine and returns
     * name of template file "order_paypal.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData["sOxid"] = $this->getEditObjectId();

        /**
         * @var $oxorder \Order
         */
        $oxorder = $this->getEditObject();

        if ($oxorder->getAfterpayOrder()->isLoaded()) {
            $this->_aViewData["oOrder"] = $oxorder;
            $this->_aViewData["oAfterpayOrder"] = $oxorder->getAfterpayOrder();
            $this->smartyAssignOrderDetails();
        } else {
            $this->_aViewData['sMessage'] = Registry::getLang()->translateString("AFTERPAY_ONLY_FOR_AFTERPAY_PAYMENT");
        }

        return "order_afterpay.tpl";
    }

    /**
     * Returns editable order object
     *
     * @return Order
     */
    public function getEditObject()
    {
        $soxId = $this->getEditObjectId();
        if ($this->_oEditObject === null && isset($soxId) && $soxId != "-1") {
            $this->_oEditObject = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
            $this->_oEditObject->load($soxId);
        }

        return $this->_oEditObject;
    }

    /**
     * Capture editObjects AfterpayPayment completely
     */
    public function smartyAssignOrderDetails()
    {
        /**
         * @var CaptureService $service
         */
        $service = $this->getOrderDetailsService();

        $response = $service->getOrderDetails();

        // Lists based on prior actions
        $this->_aViewData["aArvatoAllOrderItems"] =
            $response->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_ALLITEMS, true);

        $this->_aViewData["aArvatoCapturedOrderItems"] =
            $response->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CAPTURED, true);

        $this->_aViewData["aArvatoRefundedOrderItems"] =
            $response->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_REFUNDED, true);

        foreach ($this->_aViewData["aArvatoAllOrderItems"] as $productId => &$item) {
            $item->orderedQty = $item->quantity;
            $item->capturedQty = $this->_aViewData["aArvatoCapturedOrderItems"][$productId]->quantity ?: 0;
            $item->refundedQty = $this->_aViewData["aArvatoRefundedOrderItems"][$productId]->quantity ?: 0;
            $item->voidedQty = 0;//TODO: $this->_aViewData["aArvatoRefundedOrderItems"][$productId]->quantity ?: 0;
        }

        // Lists based od possible actions

        if ($this->getEditObject()->getAfterpayOrder()->getStatus() != \Arvato\AfterpayModule\Application\Model\AfterpayOrder::AFTERPAYSTATUS_AUTHORIZATIONVOIDED) {
            $this->_aViewData["aArvatoOrderItemsCanRefund"] =
                $response->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CANREFUND, false);

            $this->_aViewData["aArvatoOrderItemsCanCaptureOrVoid"] =
                $response->getOrderItems(
                    \Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CANCAPTUREORVOID,
                    true
                );

            $this->_aViewData["bArvatoCanFreeRefund"] = $response->canFreeRefund();
        } else {
            $this->_aViewData['sMessage'] = Registry::getLang()->translateString("AFTERPAY_ORDER_ALREADY_VOIDED");
        }
    }

    /**
     * Handels Admin calls on orderitem-based actions (e.g. capturthese items, refund those items.
     *
     * @return null and irrelevant, all return data passed via smarty
     */
    public function orderitemaction()
    {

        $aOrderItemQuantities = $this->getFromRequest('orderitemquantity');
        $sOderItemAction = $this->getFromRequest('oderitemaction');

        $orderDetailsResponse = $this->getOrderDetailsService()->getOrderDetails();

        if ('capture' == $sOderItemAction) {
            return $this->orderitemactionCapture($orderDetailsResponse, $aOrderItemQuantities);
        }

        if ('refund' == $sOderItemAction) {
            return $this->orderitemactionRefund($orderDetailsResponse, $aOrderItemQuantities);
        }

        if ('void' == $sOderItemAction) {
            return $this->orderitemactionVoid($orderDetailsResponse, $aOrderItemQuantities);
        }
        return null;
    }

    /**
     * @see oreritemaction()
     *
     * @param $orderDetailsResponse
     * @param $aOrderItemQuantities
     *
     * @return null No return value
     */
    protected function orderitemactionCapture($orderDetailsResponse, $aOrderItemQuantities)
    {
        // Get full data set of every order item
        $aApiOrderItems = $orderDetailsResponse->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CANCAPTUREORVOID);

        // Set the capture quantity of every item to 0
        array_walk($aApiOrderItems, function (&$e) {
            $e->quantity = 0;
        });

        // Apply quantites from shop owners request
        foreach ($aOrderItemQuantities as $sProductId => $fQuantity) {
            $aApiOrderItems[$sProductId]->quantity = (float)$fQuantity;
        }

        // Unset all order items that have no quantity to capture
        $aApiOrderItems = array_filter($aApiOrderItems, function ($e) {
            return (bool)$e->quantity;
        });

        // Run capture with order items set
        $this->capture($aApiOrderItems);

        return null;
    }

    /**
     * @see orderitemaction()
     *
     * @param $orderDetailsResponse
     * @param $aOrderItemQuantities
     *
     * @return null|void
     */
    protected function orderitemactionRefund($orderDetailsResponse, $aOrderItemQuantities)
    {
        $sCaptureNo = $this->getFromRequest('captureNo');

        // Get full data set of every order item
        $aApiOrderItems = $orderDetailsResponse->getOrderItems(
            \Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_ALLITEMS,
            true
        );

        // Set the capture quantity of every item to 0
        array_walk($aApiOrderItems, function (&$e) {
            $e->quantity = 0;
        });
        unset($e);

        // Apply quantites from shop owners request
        foreach ($aOrderItemQuantities as $sProductId => $fQuantity) {
            $aApiOrderItems[$sProductId]->quantity = (float)$fQuantity;
        }

        // Unset all order items that have no quantity to capture
        $aApiOrderItems = array_filter($aApiOrderItems, function ($e) {
            return 0 < $e->quantity;
        });

        if (!count($aApiOrderItems)) {
            $this->_aViewData['aErrorMessages'] = 'No items selected for refund.';
            return;
        }

        // Run capture with order items set
        $this->refund(null, $aApiOrderItems, $sCaptureNo);

        return null;
    }

    /**
     * @see orderitemaction()
     *
     * @param $orderDetailsResponse
     * @param $aOrderItemQuantities
     */
    protected function orderitemactionVoid($orderDetailsResponse, $aOrderItemQuantities)
    {
        // Get full data set of every order item
        $aApiOrderItems = $orderDetailsResponse->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CANCAPTUREORVOID);

        // Set the capture quantity of every item to 0
        array_walk($aApiOrderItems, function (&$e) {
            $e->quantity = 0;
        });

        // Apply quantites from shop owners request
        foreach ($aOrderItemQuantities as $sProductId => $fQuantity) {
            $aApiOrderItems[$sProductId]->quantity = (float)$fQuantity;
        }

        // Unset all order items that have no quantity to capture
        $aApiOrderItems = array_filter($aApiOrderItems, function ($e) {
            return (bool)$e->quantity;
        });

        // Run capture with order items set
        $this->void($aApiOrderItems);

        return;
    }

    /**
     * Capture editObjects AfterpayPayment completely, or selected items only.
     *
     * @param array|null $aOrderItems Skip for full capture
     */
    public function capture(array $aOrderItems = null)
    {
        /**
         * @var CaptureService $service
         */
        $service = $this->getCapturePaymentService();

        $sRecordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        $response = $service->capture($sRecordedApiKey, $aOrderItems);
        $capturedAmout = $response->getCapturedAmount();

        if (is_numeric($capturedAmout) && $capturedAmout > 0) {
            $this->_aViewData['oCaptureSuccess'] = $response;
            return;
        }

        $this->_aViewData['aErrorMessages'] = $service->getErrorMessages();
        if (!$this->_aViewData['aErrorMessages']) {
            $this->_aViewData['aErrorMessages'] = $response->getErrors();
        }
    }

    /**
     * Void editObjects AfterpayPayment completely, or selected items only
     *
     * @param array|null $aOrderItems Skip for full capture
     */
    public function void(array $aOrderItems = null)
    {
        /**
         * @var CaptureService $service
         */
        $service = $this->getVoidPaymentService();

        $sRecordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        $response = $service->void($sRecordedApiKey, $aOrderItems);

        $totalAuthorizedAmount = $response->getTotalAuthorizedAmount();

        if (is_numeric($totalAuthorizedAmount) && $totalAuthorizedAmount > 0) {
            $this->_aViewData['bVoidSuccess'] = true;
            $this->_aViewData['bVoidSuccessAuthAmountLeft'] = $totalAuthorizedAmount;
            return;
        }

        $this->_aViewData['aErrorMessages'] = $service->getErrorMessages();
        if (!$this->_aViewData['aErrorMessages']) {
            $this->_aViewData['aErrorMessages'] = $response->getErrors();
        }
    }

    /**
     * @param string|null $trackingId Tracking ID as provided by package carrier. Leave empty to get from request, fill
     *     for unit tests.
     * @param string|null $shippingCompany Tracking company name. Leave empty to get from request or settings, fill for
     *     unit tests.
     */
    public function captureshipping($trackingId = null, $shippingCompany = null)
    {
        $sRecordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        $trackingId = $this->getFromRequest('oxtrackcode', $trackingId);
        $shippingCompany = $this->getFromRequest('shippingcompany', $shippingCompany);
        $shippingCompany = $this->getFromConfig('arvatoAfterpayApiDefaultShippingCompany', $shippingCompany);

        /**
         * @var CaptureShippingService $service
         */
        $service = $this->getCaptureShippingService();
        $response = $service->captureShipping($trackingId, $sRecordedApiKey, $shippingCompany);

        $shippingNumber = $response->getShippingNumber();

        if (is_numeric($shippingNumber) && $shippingNumber > 0) {
            $this->_aViewData['oCaptureShippingSuccess'] = $response;
            return;
        }

        $this->_aViewData['aErrorMessages'] = $service->getErrorMessages();
        if (!$this->_aViewData['aErrorMessages']) {
            $this->_aViewData['aErrorMessages'] = $response->getErrors();
        }
    }

    /**
     * Execute refund with data provided by backends html form.
     *
     * @param null $vatSplittedRefunds
     * @param array $aOrderItems
     *
     * @return null , Feedback provided via template vars
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     * @internal param array|null $aApiOrderItems
     *
     */
    public function refund($vatSplittedRefunds = null, array $aOrderItems = null, $sCaptureNo = null)
    {
        $sRecordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        if ($vatSplittedRefunds && $aOrderItems) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Provide either $vatSplittedRefunds or $aOrderItems, not both');
        }

        $vatSplittedRefunds = $vatSplittedRefunds ?: Registry::getConfig()->getRequestParameter('refunds');
        $service = $this->getRefundService();
        $response = $service->refund($vatSplittedRefunds, $sRecordedApiKey, $aOrderItems, $sCaptureNo);
        $refundNumbers = $response->getRefundNumbers();

        if (is_array($refundNumbers) && count($refundNumbers) > 0) {
            $this->_aViewData['oRefundSuccess'] = $response;
            return;
        }

        $this->_aViewData['aErrorMessages'] = $service->getErrorMessages() ?: $response->getErrors();

        return null;
    }

    /**
     * @return string module setting getter
     */
    public function getDefaultShippingCompany()
    {
        return Registry::getConfig()->getConfigParam('arvatoAfterpayApiDefaultShippingCompany');
    }

    /**
     * @return string module setting getter
     */
    public function getDefaultRefundDescription()
    {
        return Registry::getConfig()->getConfigParam('arvatoAfterpayApiDefaultRefundDescription');
    }

    /**
     * @param array $aOrderArticles
     *
     * @return array An array with all the vat percentages used in current edit object, e.g. [19] or [7,19]
     */
    public function getRefundVatPercentages(
        $aOrderArticles = null
    ) {
        $aOrderArticles = $aOrderArticles ?: $this->getEditObject()->getOrderArticles();
        $aVatPercentages = [];
        foreach ($aOrderArticles as $orderArticle) {
            /** @var oxOrderArticle $orderArticle */
            $aVatPercentages[] = $orderArticle->oxorderarticles__oxvat->value;
        }
        $aVatPercentages = array_unique($aVatPercentages);
        $aVatPercentages = array_values($aVatPercentages);
        return $aVatPercentages;
    }

    /**
     * Template getter for price formatting
     *
     * @param double $dPrice price
     *
     * @return string
     */
    public function formatPrice(
        $dPrice
    ) {
        return Registry::getLang()->formatCurrency($dPrice);
    }

    /**
     * @return CaptureService
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function getCapturePaymentService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\CaptureService::class, $this->getEditObject());
    }

    /**
     * @return VoidService
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function getVoidPaymentService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\VoidService::class, $this->getEditObject());
    }

    /**
     * @return OrderDetailsService
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function getOrderDetailsService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\CoreOrderDetailsService::class, $this->getEditObject());
    }

    /**
     * @return CaptureShippingService
     * @codeCoverageIgnore Deliberately untested, since mocked
     */
    protected function getCaptureShippingService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\CaptureShippingService::class, $this->getEditObject());
    }

    /**
     * @return RefundService
     * @codeCoverageIgnore
     */
    protected function getRefundService()
    {
        return oxNew(\Arvato\AfterpayModule\Core\RefundService::class, $this->getEditObject());
    }

    /**
     * @return mixed
     */
    public function getFromRequest(
        $param,
        $default = null
    ) {
        $return = Registry::getConfig()->getRequestParameter($param);
        return $return ?: $default;
    }

    /**
     * @return mixed
     */
    public function getFromConfig(
        $param,
        $default
    ) {
        $return = Registry::getConfig()->getConfigParam($param);
        return $return ?: $default;
    }
}
