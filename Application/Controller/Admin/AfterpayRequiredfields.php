<?php
/**
 * ${CARET}
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @package
 * @copyright       ©2022 norisk GmbH
 *
 * @author          Anas Sadek <asadek@noriskshop.de>
 */

namespace Arvato\AfterpayModule\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

class AfterpayRequiredfields extends AdminController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'afterpay_requiredfields.tpl';

    /**
     * __construct
     * -----------------------------------------------------------------------------------------------------------------
     * constructor; sends ga data
     *
     * @compatibleOxidVersion 6.0
     */
    public function __construct()
    {
        parent::__construct();
    }
}
