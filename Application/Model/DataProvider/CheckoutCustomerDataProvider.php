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

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class AvailableInstallmentPlansDataProvider
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 *
 * @codeCoverageIgnore
 */
class CheckoutCustomerDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the customer of an order.
     *
     * @param \OxidEsales\Eshop\Application\Model\User $user
     * @param string $language
     *
     * @return CheckoutCustomerEntity
     */
    public function getCustomer(\OxidEsales\Eshop\Application\Model\User $user, $language)
    {
        $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class);
        $dataObject->setCustomerCategory(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);
        $dataObject->setAddress(oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AddressDataProvider::class)->getUserAddress($user));
        $dataObject->setFirstName($user->oxuser__oxfname->value);
        $dataObject->setLastName($user->oxuser__oxlname->value);
        $dataObject->setCustomerNumber($user->oxuser__oxcustnr->value);
        $dataObject->setSalutation(strtoupper($user->oxuser__oxsal->value));
        $dataObject->setEmail($user->oxuser__oxusername->value);
        $dataObject->setConversationLanguage($language);

        /////////////////////////
        // Handle Dyn Values

        $aDynValues = Registry::getSession()->getVariable('dynvalue');

        $aPaymentIdMapping = [
            'afterpayinstallment' => 'Installments',
            'afterpayinvoice'     => 'Invoice',
            'afterpaydebitnote'   => 'Debit'
        ];

        $sPaymentId = $aPaymentIdMapping[Registry::getSession()->getVariable('paymentid')];

        // Phone

        ($phone = $user->oxuser__oxmobfon->value) || ($phone = $user->oxuser__oxfon->value) || ($phone = $user->oxuser__oxprivfon->value);
        if (isset($aDynValues['apfon'][$sPaymentId])) {
            $phone = $aDynValues['apfon'][$sPaymentId];
        }
        $dataObject->setPhone($phone);

        // Birthday

        $birthdate = $user->oxuser__oxbirthdate->value;

        if (isset($aDynValues['apbirthday'][$sPaymentId])) {
            $birthdate = $aDynValues['apbirthday'][$sPaymentId];

            // Target: yyyy-mm-dd

            $aBirthdateEN = explode('-', $birthdate); //mm-dd-yyyy
            $aBirthdateDE = explode('.', $birthdate); //dd.mm.yyyy

            if ($aBirthdateEN && is_array($aBirthdateEN) && 3 == count($aBirthdateEN)) {
                $birthdate = $aBirthdateEN[2] . '-' . $aBirthdateEN[0] . '-' . $aBirthdateEN[1];
            } elseif ($aBirthdateDE && is_array($aBirthdateDE) && 3 == count($aBirthdateDE)) {
                $birthdate = $aBirthdateDE[2] . '-' . $aBirthdateDE[1] . '-' . $aBirthdateDE[0];
            }
        }

        if (!empty($birthdate) && $birthdate != '0000-00-00') {
            $dataObject->setBirthDate($birthdate);
        }

        // SSN

        if (isset($aDynValues['apssn'][$sPaymentId])) {
            $ssn = $aDynValues['apssn'][$sPaymentId];
            $dataObject->setIdentificationNumber($ssn);
        }

        // Profile Tracking

        if (!isAdmin() && Registry::getConfig()->getConfigParam('arvatoAfterpayProfileTrackingEnabled')) {
            $riskData = new \stdClass();
            $riskData->profileTrackingId = 'md5' . md5(Registry::getSession()->getId());
            $riskData->ipAddress = $_SERVER['REMOTE_ADDR'];
            $dataObject->setRiskData($riskData);
        }

        return $dataObject;
    }

    /**
     * Gets the delivery customer of an order.
     *
     * @param \OxidEsales\Eshop\Application\Model\User $user
     * @param string $language
     *
     * @return CheckoutCustomerEntity
     */
    public function getDeliveryCustomer(\OxidEsales\Eshop\Application\Model\User $user, $language)
    {
        /** @var oxAddress $address */
        $address = $user->getSelectedAddress();

        if (!empty($address)) {
            $dataObject = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class);
            $dataObject->setCustomerCategory(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::CUSTOMER_CATEGORY_PERSON);
            $dataObject->setAddress(oxNew(\Arvato\AfterpayModule\Application\Model\DataProvider\AddressDataProvider::class)->getDeliveryAddress($user));
            $dataObject->setFirstName($address->oxaddress__oxfname->value);
            $dataObject->setLastName($address->oxaddress__oxlname->value);
            $dataObject->setCustomerNumber($user->oxuser__oxcustnr->value);
            $dataObject->setSalutation(strtoupper($user->oxuser__oxsal->value));
            $dataObject->setEmail($user->oxuser__oxusername->value);
            $dataObject->setPhone($address->oxaddress__oxfon->value);

            $birthdate = $user->oxuser__oxbirthdate->value;
            if (!empty($birthdate) && $birthdate != '0000-00-00') {
                $dataObject->setBirthDate($birthdate);
            }
            $dataObject->setConversationLanguage($language);

            return $dataObject;
        } else {
            return $this->getCustomer($user, $language);
        }
    }
}
