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
 * @author    ©2020 norisk GmbH
 * @link
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\Entity\ValidateBankAccountResponseEntity;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class ValidateBankAccountService
 */
class ValidateBankAccountService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * @param string $IBAN
     * @param string $BIC
     *
     * @return ValidateBankAccountResponseEntity
     */
    public function validate($IBAN, $BIC)
    {
        $data = $this->getRequestData($IBAN, $BIC);
        $client = $this->getClient();
        $response = $client->execute($data);
        $this->_entity = $this->parseResponse($response);
        return $this->getEntity();
    }

    /**
     * Returns hardcoded "true" if in sandbox mode, since sandbox would always fail.
     *
     * @param string $IBAN
     * @param string $BIC
     *
     * @return bool
     */
    public function isValid($IBAN, $BIC)
    {
        if (Registry::getConfig()->getConfigParam('arvatoAfterpayApiSandboxMode')) {
            return true;
        }

        $validateBankAccountResponseEntity = $this->validate($IBAN, $BIC);

        if ($validateBankAccountResponseEntity instanceof ValidateBankAccountResponseEntity) {
            return $validateBankAccountResponseEntity->getIsValid();
        }

        return false;
    }

    /**
     * @param $IBAN
     * @param $BIC
     *
     * @return object
     * @codeCoverageIgnore mock helper method
     */
    protected function getRequestData($IBAN, $BIC)
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\ValidateBankAccountDataProvider::class)->getDataObject($IBAN, $BIC)->exportData();
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
