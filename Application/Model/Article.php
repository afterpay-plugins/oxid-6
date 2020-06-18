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

class Article extends Article_parent
{

    /**
     * @return string ProductGroup Article specific, or if no such is defined, by category
     */
    public function getAfterpayProductGroup()
    {

        // Article
        if ($this->oxarticles__aapproductgroup->value) {
            return $this->oxarticles__aapproductgroup->value;
        }

        // Parent Article
        if ($this->getParentArticle() && $this->getParentArticle()->oxarticles__aapproductgroup->value) {
            return $this->getParentArticle()->oxarticles__aapproductgroup->value;
        }

        // Main Category
        if ($this->getCategory() && $this->getCategory()->oxcategories__aapproductgroup->value) {
            return $this->getCategory()->oxcategories__aapproductgroup->value;
        }

        // Any Category
        $oCatTmp = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        foreach ($this->getCategoryIds() as $sID) {
            if ($oCatTmp->load($sID) && $oCatTmp->oxcategories__aapproductgroup->value) {
                return $oCatTmp->oxcategories__aapproductgroup->value;
            }
        }
        return null;
    }
}
