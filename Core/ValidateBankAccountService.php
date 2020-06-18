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

namespace Arvato\AfterpayModule\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class ValidateBankAccountService
 */
class ValidateBankAccountService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * @param string $sIBAN
     * @param string $sBIC
     *
     * @return ValidateBankAccountResponseEntity
     */
    public function validate($sIBAN, $sBIC)
    {
        $data = $this->getRequestData($sIBAN, $sBIC);
        $client = $this->getClient();
        $response = $client->execute($data);
        $this->_entity = $this->parseResponse($response);
        return $this->getEntity();
    }

    /**
     * Returns hardcoded "true" if in sandbox mode, since sandbox would always fail.
     *
     * @param string $sIBAN
     * @param string $sBIC
     *
     * @return bool
     */
    public function isValid($sIBAN, $sBIC)
    {
        if (Registry::getConfig()->getConfigParam('arvatoAfterpayApiSandboxMode')) {
            return true;
        }

        $ValidateBankAccountResponseEntity = $this->validate($sIBAN, $sBIC);

        if ($ValidateBankAccountResponseEntity instanceof ValidateBankAccountResponseEntity) {
            return $ValidateBankAccountResponseEntity->getIsValid();
        }

        return false;
    }

    /**
     * @param $sIBAN
     * @param $sBIC
     *
     * @return object
     * @codeCoverageIgnore mock helper method
     */
    protected function getRequestData($sIBAN, $sBIC)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\ValidateBankAccountDataProvider::class)->getDataObject($sIBAN, $sBIC)->exportData();
    }

    /**
     * @return WebServiceClient
     * @codeCoverageIgnore mock helper method
     */
    protected function getClient()
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getValidateBankAccountClient();
    }
}
