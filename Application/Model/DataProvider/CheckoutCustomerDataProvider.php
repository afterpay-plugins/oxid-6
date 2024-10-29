<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity;
use OxidEsales\Eshop\Application\Model\Address;
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
     * @param bool $trackingEnabled
     *
     * @return CheckoutCustomerEntity
     */
    public function getCustomer(\OxidEsales\Eshop\Application\Model\User $user, $language, $trackingEnabled)
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

        $dynValues = Registry::getSession()->getVariable('dynvalue');

        $paymentIdMapping = [
            'afterpayinstallment' => 'Installments',
            'afterpayinvoice'     => 'Invoice',
            'afterpaydebitnote'   => 'Debit'
        ];

        $paymentId = $paymentIdMapping[Registry::getSession()->getVariable('paymentid')];

        // Phone

        ($phone = $user->oxuser__oxmobfon->value) || ($phone = $user->oxuser__oxfon->value) || ($phone = $user->oxuser__oxprivfon->value);
        if (isset($dynValues['apfon'][$paymentId])) {
            $phone = $dynValues['apfon'][$paymentId];
        }
        $dataObject->setPhone($phone);

        // Birthday

        $birthdate = $user->oxuser__oxbirthdate->value;

        if (isset($dynValues['apbirthday'][$paymentId])) {
            $birthdate = $dynValues['apbirthday'][$paymentId];

            // Target: yyyy-mm-dd
            $birthdate = $this->_prepareBirthDate($birthdate);
        }

        if (!empty($birthdate) && $birthdate != '0000-00-00') {
            $dataObject->setBirthDate($birthdate);
        }

        // SSN

        if (isset($dynValues['apssn'][$paymentId])) {
            $ssn = $dynValues['apssn'][$paymentId];
            $dataObject->setIdentificationNumber($ssn);
        }

        // Profile Tracking
        if (!isAdmin() && $trackingEnabled) {
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
        /** @var Address $address */
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

    /**
     * Convert birth Date to YYYY-MM-DD
     *
     * @param string $birthdate
     * @return string
     */
    protected function _prepareBirthDate(string $birthdate): ?string
    {
        $symbols = [
            '-' => 'mdy', // mm-dd-yyyy
            '.' => 'dmy', // dd.mm.yyyy
            '/' => 'dmy'  // dd/mm/yyyy
        ];

        $delimiter = '';
        $format = '';

        foreach ($symbols as $symbol => $fmt) {
            if (strpos($birthdate, $symbol) !== false) {
                $delimiter = $symbol;
                $format = $fmt;
                break;
            }
        }

        if (!$delimiter) {
            return null;
        }

        $birthdateArray = explode($delimiter, $birthdate);

        if ($birthdateArray && count($birthdateArray) === 3) {
            if ($format === 'mdy') {
                return "{$birthdateArray[2]}-{$birthdateArray[0]}-{$birthdateArray[1]}";
            } elseif ($format === 'dmy') {
                return "{$birthdateArray[2]}-{$birthdateArray[1]}-{$birthdateArray[0]}";
            }
        }

        return null;
    }
}
