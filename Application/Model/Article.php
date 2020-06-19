<?php

/**
 *
*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
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
        $catTmp = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        foreach ($this->getCategoryIds() as $sID) {
            if ($catTmp->load($sID) && $catTmp->oxcategories__aapproductgroup->value) {
                return $catTmp->oxcategories__aapproductgroup->value;
            }
        }
        return null;
    }
}
