<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\DataProvider\AvailableInstallmentPlansDataProvider;
use Arvato\AfterpayModule\Application\Model\Entity\Entity;
use Arvato\AfterpayModule\Core\Exception\CurlException;

/**
 * Class AvailableInstallmentPlansService
 */
class AvailableInstallmentPlansService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * @param double $amount
     *
     * @return Entity
     * @throws CurlException
     * @internal param string $BIC
     *
     * @internal param string $IBAN
     */
    public function getAvailableInstallmentPlans(float $amount): Entity
    {
        $dataObject = $this->getAvailableInstallmentPlansDataProvider()->getDataObject($amount);
        $data = $dataObject->exportData();
        $client = $this->getAvailableInstallmentPlansClient();
        $response = $client->execute($data);

        if($response != null) {
            $this->_entity = $this->parseResponse($response);
        }

        return $this->getEntity();
    }

    /////////////////////////////////////////////////////
    // UNIT TEST HELPERS - all uncovered
    // @codeCoverageIgnoreStart

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return AvailableInstallmentPlansDataProvider
     */
    protected function getAvailableInstallmentPlansDataProvider()
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AvailableInstallmentPlansDataProvider::class);
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return WebServiceClient
     */
    protected function getAvailableInstallmentPlansClient()
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getAvailableInstallmentPlansClient();
    }
}
