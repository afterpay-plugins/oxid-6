<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

/**
 * Class CreateContractService
 */
class CreateContractService extends \Arvato\AfterpayModule\Core\Service
{

    protected $_afterpayCheckoutId;

    /**
     * Standard constructor.
     *
     * @param string $afterpayCheckoutId
     */
    public function __construct($afterpayCheckoutId)
    {
        $this->_afterpayCheckoutId = $afterpayCheckoutId;
    }
}
