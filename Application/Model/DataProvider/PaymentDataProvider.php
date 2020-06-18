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

/**
 * Class PaymentDataProvider: Data provider for payment data.
 */
class PaymentDataProvider extends \Arvato\AfterpayModule\Application\Model\DataProvider\DataProvider
{
    /**
     * Gets the payment data for a user.
     *
     * @param string $paymentId paymentId - Used for Installment, Debit and Installment
     *
     * @param string $sIBAN - Used for Debit and Installment
     * @param string $sBIC - Used for Debit and Installment
     *
     * @param int $iSelectedInstallmentPlanProfileId - Used for Installment
     * @param int $iNumberOfInstallments - Used for Installment
     *
     * @return PaymentEntity
     * @throws PaymentException
     */
    public function getPayment(
        $paymentId,
        $sIBAN = null,
        $sBIC = null,
        $iSelectedInstallmentPlanProfileId = null,
        $iNumberOfInstallments = null
    ) {
        if ('afterpayinvoice' === $paymentId) {
            $payment = $this->createInvoicePayment();
        } elseif ('afterpaydebitnote' === $paymentId) {
            $payment = $this->createDebitNotePayment($sIBAN, $sBIC);
        } elseif ('afterpayinstallment' === $paymentId) {
            $payment = $this->createInstallmentPayment(
                $sIBAN,
                $sBIC,
                $iSelectedInstallmentPlanProfileId,
                $iNumberOfInstallments
            );
        } else {
            throw new \Arvato\AfterpayModule\Core\Exception\PaymentException('Unknown Payment Type ' . $paymentId);
        }
        return $payment;
    }

    /**
     * @param $sIBAN
     * @param $sBIC
     *
     * @return PaymentEntity $payment
     */
    public function createDebitNotePayment($sIBAN, $sBIC)
    {
        $payment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);

        $payment->setType(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_DEBITNOTE);

        if ($sIBAN && $sBIC) {
            $directDebit = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\BankAccountEntity::class);
            $directDebit->setBankAccount($sIBAN);
            $directDebit->setBankCode($sBIC);
            $payment->setDirectDebit($directDebit);
        }

        return $payment;
    }

    /**
     * @return PaymentEntity
     */
    public function createInvoicePayment()
    {
        $payment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);
        $payment->setType(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_INVOICE);
        return $payment;
    }

    /**
     * @param $sIBAN
     * @param $sBIC
     * @param $iSelectedInstallmentPlanProfileId
     * @param $iNumberOfInstallments
     *
     * @return PaymentEntity
     */
    public function createInstallmentPayment(
        $sIBAN,
        $sBIC,
        $iSelectedInstallmentPlanProfileId,
        $iNumberOfInstallments
    ) {
        $payment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);
        $payment->setType(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_INSTALLMENT);

        if ($sIBAN && $sBIC) {
            $directDebit = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\BankAccountEntity::class);
            $directDebit->setBankAccount($sIBAN);
            $directDebit->setBankCode($sBIC);
            $payment->setDirectDebit($directDebit);
        }

        if ($iSelectedInstallmentPlanProfileId || $iNumberOfInstallments) {
            $installment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\Entity::class);
            $iSelectedInstallmentPlanProfileId && $installment->setProfileNo($iSelectedInstallmentPlanProfileId);
            $iNumberOfInstallments && $installment->setNumberOfInstallments($iNumberOfInstallments);
            $payment->setInstallment($installment);
        }
        return $payment;
    }
}
