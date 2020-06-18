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
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 *
 * @codeCoverageIgnore
 */
class OrderDetailsResponseEntity extends \Arvato\AfterpayModule\Application\Model\Entity\Entity
{

    // Lists based on prior actions
    public const ORDERITEM_FILTER_ALLITEMS = false;
    public const ORDERITEM_FILTER_CAPTURED = 'ORDERITEM_FILTER_CAPTURED';
    public const ORDERITEM_FILTER_REFUNDED = 'ORDERITEM_FILTER_REFUNDED';

    // Lists based od possible actions
    public const ORDERITEM_FILTER_CANREFUND = 'ORDERITEM_FILTER_CANREFUND';
    public const ORDERITEM_FILTER_CANCAPTUREORVOID = 'ORDERITEM_FILTER_CANCAPTUREORVOID';

    /**
     * @param string $filter What data to show. @see class constants
     * @param bool $bMerge True to have flat array of all articles. False to have them in subarrays by capture id
     *
     * @return array
     */
    public function getOrderItems($filter = null, $bMerge = true)
    {

        // Lists based on prior actions
        if (!$filter) {
            return $this->getAllItems();
        } elseif (self::ORDERITEM_FILTER_REFUNDED == $filter) {
            return $this->getRefundedItems($bMerge);
        } elseif (self::ORDERITEM_FILTER_CAPTURED == $filter) {
            return $this->getCapturedItems($bMerge);
        }

        // Lists based od possible actions
        if (self::ORDERITEM_FILTER_CANREFUND == $filter) {
            $aCapturedUnrefundedItems = $this->getCapturedUnrefundedItems($bMerge);

            foreach ($aCapturedUnrefundedItems as $k => $ignore) {
                $aCapturedUnrefundedItems[$k] = clone($aCapturedUnrefundedItems[$k]);
                foreach ($aCapturedUnrefundedItems[$k]->captureItems as $kk => $oCaptureItem) {
                    $oCaptureItem = clone($oCaptureItem);
                    if (!$oCaptureItem->leftToCaptureQuantity) {
                        unset($aCapturedUnrefundedItems[$k]->captureItems[$kk]);
                    }
                }
                if (!count($aCapturedUnrefundedItems[$k]->captureItems)) {
                    unset($aCapturedUnrefundedItems[$k]);
                }
            }

            return $aCapturedUnrefundedItems;
        } elseif (self::ORDERITEM_FILTER_CANCAPTUREORVOID == $filter) {
            $allItems = $this->getAllItems();
            $capturedItems = $this->getCapturedItems(true);
            $aNonCapturedItems = $this->subtractItems($allItems, $capturedItems);
            return $this->subtractItems($aNonCapturedItems, $this->getVoidedItems());
        }
        return null;
    }

    public function canFreeRefund()
    {
        return 1 === count($this->getCaptures()) && 0 === count($this->getRefunds());
    }

    /**
     * @param $bMerge
     *
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException::class
     */
    protected function getCapturedUnrefundedItems($bMerge)
    {

        if ($bMerge) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException('Cannot merge Captures. This method only supports hirarchical listing by captures.');
        }

        // Gather Captures, sorted by capture number

        $aCaptures = $this->getCaptures();

        if (!isset($aCaptures) || !is_array($aCaptures) || !count($aCaptures)) {
            return [];
        }

        $aCapturesByCaptureNumber = [];
        foreach ($aCaptures as $oCapture) {
            $aCapturesByCaptureNumber[$oCapture->captureNumber] = clone($oCapture);
            // Set productId as captureItemKey
            foreach ($aCapturesByCaptureNumber[$oCapture->captureNumber]->captureItems as $k => $v) {
                $aCapturesByCaptureNumber[$oCapture->captureNumber]->captureItems[$v->productId] = $v;
                $aCapturesByCaptureNumber[$oCapture->captureNumber]->captureItems[$v->productId]->leftToCaptureQuantity = $v->quantity;
                unset($aCapturesByCaptureNumber[$oCapture->captureNumber]->captureItems[$k]);
            }
        }

        // Gather refunds, sorted by capture[sic] number

        $aRefunds = $this->getRefunds();
        if (!isset($aRefunds) || !is_array($aRefunds) || !count($aRefunds)) {
            return $aCapturesByCaptureNumber;
        }

        $aRefundsByCaptureNumber = [];
        foreach ($aRefunds as $oRefund) {
            $aRefundsByCaptureNumber[$oRefund->captureNumber] = $oRefund;
            // Set productId as refundItemKey
            foreach ($aRefundsByCaptureNumber[$oRefund->captureNumber]->refundItems as $k => $v) {
                $aRefundsByCaptureNumber[$oRefund->captureNumber]->refundItems[$v->productId] = $v;
                unset($aRefundsByCaptureNumber[$oRefund->captureNumber]->refundItems[$k]);
            }
        }

        foreach ($aRefundsByCaptureNumber as $sCaptureNumber => $oRefund) {
            $aRefundItems = $oRefund->refundItems;
            if (!isset($aRefundItems) || !is_array($aRefundItems) || !count($aRefundItems)) {
                continue;
            }

            // Set left-to-capture quantity
            foreach ($aRefundItems as $oRefundItem) {
                foreach ($aCapturesByCaptureNumber[$sCaptureNumber]->captureItems as $k => &$captureItem) {
                    if ($captureItem->productId === $oRefundItem->productId) {
                        $captureItem->leftToCaptureQuantity = $captureItem->quantity - $oRefundItem->quantity;
                    } elseif (!isset($captureItem->leftToCaptureQuantity)) {
                        $captureItem->leftToCaptureQuantity = $captureItem->quantity;
                    }
                    unset($captureItem);
                }
            }
        }

        return $aCapturesByCaptureNumber;
    }

    protected function getAllItems()
    {

        if (!isset($this->getOrderDetails()->orderItems)) {
            return [];
        }

        $aAllItems = [];

        foreach ($this->getOrderDetails()->orderItems as $oItem) {
            $aAllItems[$oItem->productId] = clone($oItem);
            $oArt = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArt->load($oItem->productId);
            $aAllItems[$oItem->productId]->oxArticle = $oArt;
        }

        return $aAllItems;
    }

    protected function getCapturedItems($bMerge = true)
    {

        $aCombinedCapturedItems = [];

        $aCaptures = $this->getCaptures();
        if (!isset($aCaptures) || !is_array($aCaptures) || !count($aCaptures)) {
            return [];
        }

        foreach ($aCaptures as $captureIndex => $oCapture) {
            // RESPONSE->captures[n]

            $aCaptureItems = $oCapture->captureItems;
            if (!isset($aCaptureItems) || !is_array($aCaptureItems) || !count($aCaptureItems)) {
                continue;
            }

            foreach ($aCaptureItems as $captureItemIndex => $oCaptureItem) {
                // RESPONSE->captures[n]->captureItems[n]

                if ($bMerge && !isset($aCombinedCapturedItems[$oCaptureItem->productId])) {
                    // Ammend oxArticle to captureItem, save into flat array
                    $aCombinedCapturedItems[$oCaptureItem->productId] = clone($oCaptureItem);
                    $oArt = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $oArt->load($oCaptureItem->productId);
                    $aCombinedCapturedItems[$oCaptureItem->productId]->oxArticle = $oArt;
                } elseif ($bMerge) {
                    // Add quantity to flat array
                    $aCombinedCapturedItems[$oCaptureItem->productId]->quantity += $oCaptureItem->quantity;
                } else {
                    // No flat array, just ammend oxArticle to hierarchy
                    $oArt = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $oArt->load($oCaptureItem->productId);
                    $aCaptureItems[$captureItemIndex]->oxArticle = $oArt;
                }
            }
        }

        return $bMerge ? $aCombinedCapturedItems : $aCaptureItems;
    }

    protected function getRefundedItems($bMerge = true)
    {

        $aCombinedRefundItems = [];

        $aRefunds = $this->getRefunds();
        if (!isset($aRefunds) || !is_array($aRefunds) || !count($aRefunds)) {
            return [];
        }

        foreach ($aRefunds as $oRefund) {
            // RESPONSE->captures[n]

            $aRefundItems = $oRefund->refundItems;
            if (!isset($aRefundItems) || !is_array($aRefundItems) || !count($aRefundItems)) {
                continue;
            }

            foreach ($aRefundItems as $refundIndex => $oRefundItem) {
                // RESPONSE->captures[n]->captureItems[n]

                if ($bMerge && !isset($aCombinedRefundItems[$oRefundItem->productId])) {
                    // Ammend oxArticle to refundItem, save into flat array
                    $aCombinedRefundItems[$oRefundItem->productId] = clone($oRefundItem);
                    $oArt = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $oArt->load($oRefundItem->productId);
                    $aCombinedRefundItems[$oRefundItem->productId]->oxArticle = $oArt;
                } elseif ($bMerge) {
                    // Add quantity to flat array
                    $aCombinedRefundItems[$oRefundItem->productId]->quantity += $oRefundItem->quantity;
                } else {
                    // No flat array, just ammend oxArticle to hierarchy
                    $oArt = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $oArt->load($oRefundItem->productId);
                    $aRefundItems[$refundIndex]->oxArticle = $oArt;
                }
            }
        }

        return $bMerge ? $aCombinedRefundItems : $aRefundItems;
    }

    protected function getVoidedItems($bMerge = true)
    {
        return [];
    }

    protected function extractItemsFromCapture(\stdClass $oCapture)
    {
        $oCapture = clone($oCapture);
        $aCaptureItems = $oCapture->captureItems;

        if (!isset($aCaptureItems) || !is_array($aCaptureItems) || !count($aCaptureItems)) {
            return [];
        }

        //$CapturedItemsbyProductKey

        return $aCaptureItems;
    }

    protected function subtractItems($aAllItems, $aSubtractItems, $bMerge = true)
    {
        $aRemainingItems = $aAllItems;
        foreach ($aRemainingItems as $k => $v) {
            // Skip if there is nothing to subtract
            if (!isset($aSubtractItems[$k])) {
                continue;
            }

            // Subtract quantity
            $aRemainingItems[$k]->quantity -= $aSubtractItems[$k]->quantity;

            // Unset if quantity is zero
            if (!$aRemainingItems[$k]->quantity) {
                unset($aRemainingItems[$k]);
            }
        }

        return $aRemainingItems;
    }
}
