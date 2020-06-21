<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Entity;

/**
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 *
 * @codeCoverageIgnore
 */
class OrderDetailsResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    // Lists based on prior actions
    const ORDERITEM_FILTER_ALLITEMS = false;
    const ORDERITEM_FILTER_CAPTURED = 'ORDERITEM_FILTER_CAPTURED';
    const ORDERITEM_FILTER_REFUNDED = 'ORDERITEM_FILTER_REFUNDED';

    // Lists based od possible actions
    const ORDERITEM_FILTER_CANREFUND = 'ORDERITEM_FILTER_CANREFUND';
    const ORDERITEM_FILTER_CANCAPTUREORVOID = 'ORDERITEM_FILTER_CANCAPTUREORVOID';

    /**
     * @param string $filter What data to show. @see class constants
     * @param bool $merge True to have flat array of all articles. False to have them in subarrays by capture id
     *
     * @return array
     */
    public function getOrderItems($filter = null, $merge = true)
    {

        // Lists based on prior actions
        if (!$filter) {
            return $this->getAllItems();
        } elseif (self::ORDERITEM_FILTER_REFUNDED == $filter) {
            return $this->getRefundedItems($merge);
        } elseif (self::ORDERITEM_FILTER_CAPTURED == $filter) {
            return $this->getCapturedItems($merge);
        }

        // Lists based od possible actions
        if (self::ORDERITEM_FILTER_CANREFUND == $filter) {
            $capturedUnrefundedItems = $this->getCapturedUnrefundedItems($merge);

            foreach ($capturedUnrefundedItems as $k => $ignore) {
                $capturedUnrefundedItems[$k] = clone($capturedUnrefundedItems[$k]);
                foreach ($capturedUnrefundedItems[$k]->captureItems as $kk => $captureItem) {
                    $captureItem = clone($captureItem);
                    if (!$captureItem->leftToCaptureQuantity) {
                        unset($capturedUnrefundedItems[$k]->captureItems[$kk]);
                    }
                }
                if (!count($capturedUnrefundedItems[$k]->captureItems)) {
                    unset($capturedUnrefundedItems[$k]);
                }
            }

            return $capturedUnrefundedItems;
        } elseif (self::ORDERITEM_FILTER_CANCAPTUREORVOID == $filter) {
            $allItems = $this->getAllItems();
            $capturedItems = $this->getCapturedItems(true);
            $nonCapturedItems = $this->subtractItems($allItems, $capturedItems);
            return $this->subtractItems($nonCapturedItems, $this->getVoidedItems());
        }
        return null;
    }

    public function canFreeRefund()
    {
        return 1 === count($this->getCaptures()) && 0 === count($this->getRefunds());
    }

    /**
     * @param $merge
     *
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    protected function getCapturedUnrefundedItems($merge)
    {

        if ($merge) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Cannot merge Captures. This method only supports hirarchical listing by captures.');
        }

        // Gather Captures, sorted by capture number

        $captures = $this->getCaptures();

        if (!isset($captures) || !is_array($captures) || !count($captures)) {
            return [];
        }

        $capturesByCaptureNumber = [];
        foreach ($captures as $capture) {
            $capturesByCaptureNumber[$capture->captureNumber] = clone($capture);
            // Set productId as captureItemKey
            foreach ($capturesByCaptureNumber[$capture->captureNumber]->captureItems as $k => $v) {
                $capturesByCaptureNumber[$capture->captureNumber]->captureItems[$v->productId] = $v;
                $capturesByCaptureNumber[$capture->captureNumber]->captureItems[$v->productId]->leftToCaptureQuantity = $v->quantity;
                unset($capturesByCaptureNumber[$capture->captureNumber]->captureItems[$k]);
            }
        }

        // Gather refunds, sorted by capture[sic] number

        $refunds = $this->getRefunds();
        if (!isset($refunds) || !is_array($refunds) || !count($refunds)) {
            return $capturesByCaptureNumber;
        }

        $refundsByCaptureNumber = [];
        foreach ($refunds as $refund) {
            $refundsByCaptureNumber[$refund->captureNumber] = $refund;
            // Set productId as refundItemKey
            foreach ($refundsByCaptureNumber[$refund->captureNumber]->refundItems as $k => $v) {
                $refundsByCaptureNumber[$refund->captureNumber]->refundItems[$v->productId] = $v;
                unset($refundsByCaptureNumber[$refund->captureNumber]->refundItems[$k]);
            }
        }

        foreach ($refundsByCaptureNumber as $captureNumber => $refund) {
            $refundItems = $refund->refundItems;
            if (!isset($refundItems) || !is_array($refundItems) || !count($refundItems)) {
                continue;
            }

            // Set left-to-capture quantity
            foreach ($refundItems as $refundItem) {
                foreach ($capturesByCaptureNumber[$captureNumber]->captureItems as $k => &$captureItem) {
                    if ($captureItem->productId === $refundItem->productId) {
                        $captureItem->leftToCaptureQuantity = $captureItem->quantity - $refundItem->quantity;
                    } elseif (!isset($captureItem->leftToCaptureQuantity)) {
                        $captureItem->leftToCaptureQuantity = $captureItem->quantity;
                    }
                    unset($captureItem);
                }
            }
        }

        return $capturesByCaptureNumber;
    }

    protected function getAllItems()
    {

        if (!isset($this->getOrderDetails()->orderItems)) {
            return [];
        }

        $allItems = [];

        foreach ($this->getOrderDetails()->orderItems as $oItem) {
            $allItems[$oItem->productId] = clone($oItem);
            $art = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $art->load($oItem->productId);
            $allItems[$oItem->productId]->oxArticle = $art;
        }

        return $allItems;
    }

    protected function getCapturedItems($merge = true)
    {

        $combinedCapturedItems = [];

        $captures = $this->getCaptures();
        if (!isset($captures) || !is_array($captures) || !count($captures)) {
            return [];
        }

        foreach ($captures as $captureIndex => $capture) {
            // RESPONSE->captures[n]

            $captureItems = $capture->captureItems;
            if (!isset($captureItems) || !is_array($captureItems) || !count($captureItems)) {
                continue;
            }

            foreach ($captureItems as $captureItemIndex => $captureItem) {
                // RESPONSE->captures[n]->captureItems[n]

                if ($merge && !isset($combinedCapturedItems[$captureItem->productId])) {
                    // Ammend oxArticle to captureItem, save into flat array
                    $combinedCapturedItems[$captureItem->productId] = clone($captureItem);
                    $art = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $art->load($captureItem->productId);
                    $combinedCapturedItems[$captureItem->productId]->oxArticle = $art;
                } elseif ($merge) {
                    // Add quantity to flat array
                    $combinedCapturedItems[$captureItem->productId]->quantity += $captureItem->quantity;
                } else {
                    // No flat array, just ammend oxArticle to hierarchy
                    $art = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $art->load($captureItem->productId);
                    $captureItems[$captureItemIndex]->oxArticle = $art;
                }
            }
        }

        return $merge ? $combinedCapturedItems : $captureItems;
    }

    protected function getRefundedItems($merge = true)
    {

        $combinedRefundItems = [];

        $refunds = $this->getRefunds();
        if (!isset($refunds) || !is_array($refunds) || !count($refunds)) {
            return [];
        }

        foreach ($refunds as $refund) {
            // RESPONSE->captures[n]

            $refundItems = $refund->refundItems;
            if (!isset($refundItems) || !is_array($refundItems) || !count($refundItems)) {
                continue;
            }

            foreach ($refundItems as $refundIndex => $refundItem) {
                // RESPONSE->captures[n]->captureItems[n]

                if ($merge && !isset($combinedRefundItems[$refundItem->productId])) {
                    // Ammend oxArticle to refundItem, save into flat array
                    $combinedRefundItems[$refundItem->productId] = clone($refundItem);
                    $art = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $art->load($refundItem->productId);
                    $combinedRefundItems[$refundItem->productId]->oxArticle = $art;
                } elseif ($merge) {
                    // Add quantity to flat array
                    $combinedRefundItems[$refundItem->productId]->quantity += $refundItem->quantity;
                } else {
                    // No flat array, just ammend oxArticle to hierarchy
                    $art = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $art->load($refundItem->productId);
                    $refundItems[$refundIndex]->oxArticle = $art;
                }
            }
        }

        return $merge ? $combinedRefundItems : $refundItems;
    }

    protected function getVoidedItems($merge = true)
    {
        return [];
    }

    protected function extractItemsFromCapture(\stdClass $capture)
    {
        $capture = clone($capture);
        $captureItems = $capture->captureItems;

        if (!isset($captureItems) || !is_array($captureItems) || !count($captureItems)) {
            return [];
        }

        //$CapturedItemsbyProductKey

        return $captureItems;
    }

    protected function subtractItems($allItems, $subtractItems, $merge = true)
    {
        $remainingItems = $allItems;
        foreach ($remainingItems as $k => $v) {
            // Skip if there is nothing to subtract
            if (!isset($subtractItems[$k])) {
                continue;
            }

            // Subtract quantity
            $remainingItems[$k]->quantity -= $subtractItems[$k]->quantity;

            // Unset if quantity is zero
            if (!$remainingItems[$k]->quantity) {
                unset($remainingItems[$k]);
            }
        }

        return $remainingItems;
    }
}
