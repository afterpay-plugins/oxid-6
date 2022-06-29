<?php

namespace Arvato\AfterpayModule\Application\Controller\Admin;

use DOMElement;
use DOMXPath;

/**
 *
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @package         RolesBackendMain.php
 * @copyright       ©2022 norisk GmbH
 *
 * @author          Sven Beutel <sbeutel@noriskshop.de>
 */
class RolesBackendMain extends RolesBackendMain_parent
{
    /**
     * render
     * -----------------------------------------------------------------------------------------------------------------
     * Adds own module to roles admin controller
     *
     * @return string
     */
    public function render()
    {
        $template = parent::render();
        $currentModuleNodes = ['AFTERPAY_TITLE'];

        /* merge module's node into main menu nodes list */
        // get main menu node
        $mainMenuList = $this->getNavigation()->getDomXml()->documentElement->firstChild;
        // get ARVATOMODULES node
        $xPath = new DOMXPath($this->getNavigation()->getDomXml());
        $nodeList = $xPath->query("//*[@id='ARVATOMODULES']");

        if ($nodeList->length && $nodeList->item(0)) {
            $noriskModulesNode = $nodeList->item(0);
            for ($i = 0; $i < $noriskModulesNode->childNodes->length; $i++) {
                /** @var DOMElement $domNode */
                $domNode = $noriskModulesNode->childNodes->item($i);
                // Check if the current node's ID is one of this module's IDs
                if ($domNode instanceof DOMElement && in_array($domNode->getAttribute('id'), $currentModuleNodes, true)) {
                    $mainMenuList->appendChild(clone $domNode);
                }
            }
        }

        $this->_aViewData['adminmenu'] = $mainMenuList->childNodes;

        return $template;
    }
}
