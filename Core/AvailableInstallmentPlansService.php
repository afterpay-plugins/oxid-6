<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use Arvato\AfterpayModule\Application\Model\DataProvider\AvailableInstallmentPlansDataProvider;
use Arvato\AfterpayModule\Application\Model\Entity\AvailableInstallmentPlansResponseEntity;

/**
 * Class AvailableInstallmentPlansService
 */
class AvailableInstallmentPlansService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * @param double $amount
     *
     * @return AvailableInstallmentPlansResponseEntity
     * @internal param string $IBAN
     * @internal param string $BIC
     *
     */
    public function getAvailableInstallmentPlans($amount)
    {
        $dataObject = $this->getAvailableInstallmentPlansDataProvider()->getDataObject($amount);
        $data = $dataObject->exportData();
        $client = $this->getAvailableInstallmentPlansClient();
        $response = $client->execute($data);

        $this->_entity = $this->parseResponse($response);

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
