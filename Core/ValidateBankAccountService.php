<?php

/**
 *
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
        if (Registry::getConfig()->getConfigParam('arvatoAfterpayApiMode', 'sandbox') === 'sandbox') {
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
