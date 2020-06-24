<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model;

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
        if(!empty($this->getCategory()->getTitle()) && !empty($this->getCategoryIds())) {
            return $this->getCategory()->getTitle();
        }
        return false;
    }
}
