<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;

class Article extends Article_parent
{
    /**
     * getMainCategory
     * -----------------------------------------------------------------------------------------------------------------
     * show main category if there is one in oxobject2category
     *
     * @return bool
     */
    public function getMainCategory() {

        $categoryOfArticle = "SELECT c.OXTITLE FROM oxobject2category o2c 
                            LEFT JOIN oxcategories c ON c.OXID = o2c.OXCATNID 
                            LEFT JOIN oxarticles a ON a.OXID = o2c.OXOBJECTID 
                            WHERE a.OXID = ? 
                            ORDER BY oxtime ASC LIMIT 1";
        $categoryTitleStructure = DatabaseProvider::getDb()->getCol($categoryOfArticle, [$this->getId()]);
        $categoryTitle = $categoryTitleStructure[0];


        if(!empty($categoryTitle)) {
            return $categoryTitle;
        }
        return false;
    }
}
