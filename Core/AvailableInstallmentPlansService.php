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
