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

/**
 * Class AuthorizePaymentService: Service for external autorization of payments with AfterPay.
 */
class AuthorizePaymentService extends \Arvato\AfterpayModule\Core\Service
{

    /**
     * Standard constructor.
     *
     * @param oxSession $session
     * @param oxLang $lang
     */
    public function __construct(\OxidEsales\Eshop\Core\Session $session, \OxidEsales\Eshop\Core\Language $lang)
    {
        $this->_session = $session;
        $this->_lang = $lang;
    }

    /**
     * Performs the autorize payment call.
     *
     * @param $oOrder
     *
     * @return string Outcome
     */
    public function authorizePayment(\OxidEsales\Eshop\Application\Model\Order $oOrder)
    {
        $response = $this->executeRequestFromSessionData($oOrder);

        $this->_entity = $this->parseResponse($response);
        $this->_session->setVariable('arvatoAfterpayReservationId', $this->_entity->getReservationId());
        $this->_session->setVariable('arvatoAfterpayCheckoutId', $this->_entity->getCheckoutId());

        if ($response->customer && $response->customer->addressList) {
            $this->updateCorrectedCustomerAddress($response->customer->addressList, false);
        }

        if ($response->deliveryCustomer && $response->deliveryCustomer->addressList) {
            $this->updateCorrectedCustomerAddress($response->deliveryCustomer->addressList, true);
        }

        return $this->_entity->getOutcome();
    }

    /**
     *
     * @param oxOrder $oOrder
     *
     * @return stdClass|stdClass[]
     */
    public function executeRequestFromSessionData(\OxidEsales\Eshop\Application\Model\Order $oOrder)
    {
        $dataProvider = $this->getAuthorizePaymentDataProvider();
        $dataObject = $dataProvider->getDataObject($this->_session, $this->_lang, $oOrder);
        $data = $dataObject->exportData();

        $client = $this->getAuthorizePaymentClient();
        return $client->execute($data);
    }

    /**
     * Takes corrected customer address from API response, saves to oxUser and oxAddress.
     *
     * @param stdClass $stdClassAddress E.G.
     *     {"street":"Bahnhofstr.","streetNumber":"123","postalCode":"70736","postalPlace":"Fellbach","countryCode":"DE"}
     * @param bool $bIsDeliveryAddress billing/delivery
     */
    public function updateCorrectedCustomerAddress($stdClassAddress, $bIsDeliveryAddress = false)
    {

        if (is_array($stdClassAddress)) {
            $stdClassAddress = reset($stdClassAddress);
        }

        if (!$stdClassAddress) {
            return;
        }

        $oUser = $this->_session->getUser();

        if (!$oUser) {
            // Session lost or UnitTest
            return;
        }

        if (!$bIsDeliveryAddress) {
            isset($stdClassAddress->street) && $oUser->oxuser__oxstreet = new \OxidEsales\Eshop\Core\Field($stdClassAddress->street);
            isset($stdClassAddress->streetNumber) && $oUser->oxuser__oxstreetnr = new \OxidEsales\Eshop\Core\Field($stdClassAddress->streetNumber);
            isset($stdClassAddress->postalCode) && $oUser->oxuser__oxzip = new \OxidEsales\Eshop\Core\Field($stdClassAddress->postalCode);
            isset($stdClassAddress->postalPlace) && $oUser->oxuser__oxcity = new \OxidEsales\Eshop\Core\Field($stdClassAddress->postalPlace);
            isset($stdClassAddress->countryCode) && $oUser->oxuser__oxcountryid = new \OxidEsales\Eshop\Core\Field(oxNew(\OxidEsales\Eshop\Application\Model\Country::class)->getIdByCode($stdClassAddress->countryCode));
            $oUser->save();
        } else {
            $oxAddress = $oUser->getSelectedAddress();
            isset($stdClassAddress->street) && $oxAddress->oxaddress__oxstreet = new \OxidEsales\Eshop\Core\Field($stdClassAddress->street);
            isset($stdClassAddress->streetNumber) && $oxAddress->oxaddress__oxstreetnr = new \OxidEsales\Eshop\Core\Field($stdClassAddress->streetNumber);
            isset($stdClassAddress->postalCode) && $oxAddress->oxaddress__oxzip = new \OxidEsales\Eshop\Core\Field($stdClassAddress->postalCode);
            isset($stdClassAddress->postalPlace) && $oxAddress->oxaddress__oxcity = new \OxidEsales\Eshop\Core\Field($stdClassAddress->postalPlace);
            isset($stdClassAddress->countryCode) && $oxAddress->oxaddress__oxcountryid = new \OxidEsales\Eshop\Core\Field(oxNew(\OxidEsales\Eshop\Application\Model\Country::class)->getIdByCode($stdClassAddress->countryCode));
            $oxAddress->save();
        }

        $this->_iLastErrorNo = oxNew(\OxidEsales\Eshop\Application\Controller\OrderController::class)->getOrderStateCheckAddressConstant();
    }

    /////////////////////////////////////////////////////
    // UNIT TEST HELPERS - all uncovered
    // @codeCoverageIgnoreStart

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return AuthorizePaymentDataProvider
     */
    protected function getAuthorizePaymentDataProvider()
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AuthorizePaymentDataProvider::class);
    }

    /**
     * @codeCoverageIgnore Deliberately untested, since mocked
     * @return WebServiceClient
     */
    public function getAuthorizePaymentClient()
    {
        return oxNew(\Arvato\AfterpayModule\Core\ClientConfigurator::class)->getAuthorizePaymentClient();
    }
}
