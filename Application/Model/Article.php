<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;

class Article extends Article_parent
{

    /***
     * getMainCategory
     * -----------------------------------------------------------------------------------------------------------------
     * show main category for this article if available, otherwise some category
     *
     * @return false|string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getMainCategory() {
        if(!empty($this->getCategory()->getTitle())) {
            return $this->getCategory()->getTitle();
        }
        else {
            if (empty($this->getCategory()->getTitle()))  {
                $db = DatabaseProvider::getDb();
                return $db->getOne("SELECT oxtitle FROM oxcategories LIMIT 1");
            }
        }
    }

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
        foreach ($this->getCategoryIds() as $id) {
            if ($catTmp->load($id) && $catTmp->oxcategories__aapproductgroup->value) {
                return $catTmp->oxcategories__aapproductgroup->value;
            }
        }
        return null;
    }
}
