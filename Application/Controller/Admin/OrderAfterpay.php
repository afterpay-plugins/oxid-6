<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Controller\Admin;

use Arvato\AfterpayModule\Core\CaptureService;
use Arvato\AfterpayModule\Core\CaptureShippingService;
use Arvato\AfterpayModule\Core\OrderDetailsService;
use Arvato\AfterpayModule\Core\RefundService;
use Arvato\AfterpayModule\Core\VoidService;
use OxidEsales\Eshop\Application\Controller\Admin\OrderArticle;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

/**
 * Order class wrapper for Afterpay module
 */
class OrderAfterpay extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    protected $_oEditObject;

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
         * @var $order Order
         */
        $order = $this->getEditObject();

        if ($order->getAfterpayOrder()->isLoaded()) {
            $this->_aViewData["oOrder"] = $order;
            $this->_aViewData["oAfterpayOrder"] = $order->getAfterpayOrder();
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
        $oxId = $this->getEditObjectId();
        if ($this->_oEditObject === null && isset($oxId) && $oxId != "-1") {
            $this->_oEditObject = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
            $this->_oEditObject->load($oxId);
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
    public function orderItemAction()
    {

        $orderItemQuantities = $this->getFromRequest('orderitemquantity');
        $orderItemAction = $this->getFromRequest('oderitemaction');

        $orderDetailsResponse = $this->getOrderDetailsService()->getOrderDetails();

        if ('capture' == $orderItemAction) {
            return $this->orderItemActionCapture($orderDetailsResponse, $orderItemQuantities);
        }

        if ('refund' == $orderItemAction) {
            return $this->orderItemActionRefund($orderDetailsResponse, $orderItemQuantities);
        }

        if ('void' == $orderItemAction) {
            return $this->orderItemActionVoid($orderDetailsResponse, $orderItemQuantities);
        }
        return null;
    }

    /**
     * @see orderItemAction()
     *
     * @param $orderDetailsResponse
     * @param $orderItemQuantities
     *
     * @return null No return value
     */
    protected function orderItemActionCapture($orderDetailsResponse, $orderItemQuantities)
    {
        // Get full data set of every order item
        $apiOrderItems = $orderDetailsResponse->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CANCAPTUREORVOID);

        // Set the capture quantity of every item to 0
        array_walk($apiOrderItems, function (&$e) {
            $e->quantity = 0;
        });

        // Apply quantites from shop owners request
        foreach ($orderItemQuantities as $productId => $quantity) {
            $apiOrderItems[$productId]->quantity = (float)$quantity;
        }

        // Unset all order items that have no quantity to capture
        $apiOrderItems = array_filter($apiOrderItems, function ($e) {
            return (bool)$e->quantity;
        });

        // Run capture with order items set
        $this->capture($apiOrderItems);

        return null;
    }

    /**
     * @see orderItemAction()
     *
     * @param $orderDetailsResponse
     * @param $orderItemQuantities
     *
     * @return null|void
     */
    protected function orderItemActionRefund($orderDetailsResponse, $orderItemQuantities)
    {
        $captureNo = $this->getFromRequest('captureNo');

        // Get full data set of every order item
        $apiOrderItems = $orderDetailsResponse->getOrderItems(
            \Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_ALLITEMS,
            true
        );

        // Set the capture quantity of every item to 0
        array_walk($apiOrderItems, function (&$e) {
            $e->quantity = 0;
        });
        unset($e);

        // Apply quantites from shop owners request
        foreach ($orderItemQuantities as $productId => $quantity) {
            $apiOrderItems[$productId]->quantity = (float)$quantity;
        }

        // Unset all order items that have no quantity to capture
        $apiOrderItems = array_filter($apiOrderItems, function ($e) {
            return 0 < $e->quantity;
        });

        if (!count($apiOrderItems)) {
            $this->_aViewData['aErrorMessages'] = 'No items selected for refund.';
            return;
        }

        // Run capture with order items set
        $this->refund(null, $apiOrderItems, $captureNo);

        return null;
    }

    /**
     * @see orderItemAction()
     *
     * @param $orderDetailsResponse
     * @param $orderItemQuantities
     */
    protected function orderItemActionVoid($orderDetailsResponse, $orderItemQuantities)
    {
        // Get full data set of every order item
        $apiOrderItems = $orderDetailsResponse->getOrderItems(\Arvato\AfterpayModule\Application\Model\Entity\OrderDetailsResponseEntity::ORDERITEM_FILTER_CANCAPTUREORVOID);

        // Set the capture quantity of every item to 0
        array_walk($apiOrderItems, function (&$e) {
            $e->quantity = 0;
        });

        // Apply quantites from shop owners request
        foreach ($orderItemQuantities as $productId => $quantity) {
            $apiOrderItems[$productId]->quantity = (float)$quantity;
        }

        // Unset all order items that have no quantity to capture
        $apiOrderItems = array_filter($apiOrderItems, function ($e) {
            return (bool)$e->quantity;
        });

        // Run capture with order items set
        $this->void($apiOrderItems);

        return;
    }

    /**
     * Capture editObjects AfterpayPayment completely, or selected items only.
     *
     * @param array|null $orderItems Skip for full capture
     */
    public function capture(array $orderItems = null)
    {
        /**
         * @var CaptureService $service
         */
        $service = $this->getCapturePaymentService();

        $recordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        $response = $service->capture($recordedApiKey, $orderItems);
        $capturedAmount = $response->getCapturedAmount();

        if (is_numeric($capturedAmount) && $capturedAmount > 0) {
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
     * @param array|null $orderItems Skip for full capture
     */
    public function void(array $orderItems = null)
    {
        /**
         * @var CaptureService $service
         */
        $service = $this->getVoidPaymentService();

        $recordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        $response = $service->void($recordedApiKey, $orderItems);

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
    public function captureShipping($trackingId = null, $shippingCompany = null)
    {
        $recordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        $trackingId = $this->getFromRequest('oxtrackcode', $trackingId);
        $shippingCompany = $this->getFromRequest('shippingcompany', $shippingCompany);
        $shippingCompany = $this->getFromConfig('arvatoAfterpayApiDefaultShippingCompany', $shippingCompany);

        /**
         * @var CaptureShippingService $service
         */
        $service = $this->getCaptureShippingService();
        $response = $service->captureShipping($trackingId, $recordedApiKey, $shippingCompany);

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
     * @param array $orderItems
     *
     * @return null , Feedback provided via template vars
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     * @internal param array|null $apiOrderItems
     *
     */
    public function refund($vatSplittedRefunds = null, array $orderItems = null, $captureNo = null)
    {
        $recordedApiKey = $this->getEditObject()->getAfterpayOrder()->arvatoafterpayafterpayorder__apusedapikey->getRawValue();

        if ($vatSplittedRefunds && $orderItems) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Provide either $vatSplittedRefunds or $orderItems, not both');
        }

        $vatSplittedRefunds = $vatSplittedRefunds ?: Registry::get(Request::class)->getRequestEscapedParameter('refunds');
        $service = $this->getRefundService();
        $response = $service->refund($vatSplittedRefunds, $recordedApiKey, $orderItems, $captureNo);
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
     * @param array $orderArticles
     *
     * @return array An array with all the vat percentages used in current edit object, e.g. [19] or [7,19]
     */
    public function getRefundVatPercentages(
        $orderArticles = null
    ) {
        $orderArticles = $orderArticles ?: $this->getEditObject()->getOrderArticles();
        $vatPercentages = [];
        foreach ($orderArticles as $orderArticle) {
            /** @var OrderArticle $orderArticle */
            $vatPercentages[] = $orderArticle->oxorderarticles__oxvat->value;
        }
        $vatPercentages = array_unique($vatPercentages);
        $vatPercentages = array_values($vatPercentages);
        return $vatPercentages;
    }

    /**
     * Template getter for price formatting
     *
     * @param double $price price
     *
     * @return string
     */
    public function formatPrice(
        $price
    ) {
        return Registry::getLang()->formatCurrency($price);
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
        $return = Registry::get(Request::class)->getRequestEscapedParameter($param);
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
