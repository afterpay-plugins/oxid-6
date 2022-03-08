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

namespace Arvato\AfterpayModule\Core;

class Afterpay_Utils extends Afterpay_Utils_parent
{
    /**
     * Takes an array and builds again a string. Returns string with values.
     * overwrite standard: handles the more complicated afterpay structure
     * ex: ['apbirthday' => ['Invoice' => 'nnn'], ...]
     * - it loses the paymenttype info, until now it wasn't used in this place anyway
     *
     * @param array $aIn Initial array of strings
     *
     * @return string
     */
    public function assignValuesToText($aIn)
    {
        $sRet = "";
        reset($aIn);
        foreach ($aIn as $sKey => $sVal) {
            $sRet .= $sKey;
            $sRet .= "__";
            if (is_array($sVal)) {
                $sVal = current($sVal);
            }
            $sRet .= $sVal;
            $sRet .= "@@";
        }

        return $sRet;
    }
}
