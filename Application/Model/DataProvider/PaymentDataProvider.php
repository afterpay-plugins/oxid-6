<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity;
use Arvato\AfterpayModule\Core\Exception\PaymentException;

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
     * @param string $IBAN - Used for Debit and Installment
     * @param string $BIC - Used for Debit and Installment
     *
     * @param int $selectedInstallmentPlanProfileId - Used for Installment
     * @param int $numberOfInstallments - Used for Installment
     *
     * @return PaymentEntity
     * @throws PaymentException
     */
    public function getPayment(
        $paymentId,
        $IBAN = null,
        $BIC = null,
        $selectedInstallmentPlanProfileId = null,
        $numberOfInstallments = null
    ) {
        if ('afterpayinvoice' === $paymentId) {
            $payment = $this->createInvoicePayment();
        } elseif ('afterpaydebitnote' === $paymentId) {
            $payment = $this->createDebitNotePayment($IBAN, $BIC);
        } elseif ('afterpayinstallment' === $paymentId) {
            $payment = $this->createInstallmentPayment(
                $IBAN,
                $BIC,
                $selectedInstallmentPlanProfileId,
                $numberOfInstallments
            );
        } else {
            throw new \Arvato\AfterpayModule\Core\Exception\PaymentException('Unknown Payment Type ' . $paymentId);
        }
        return $payment;
    }

    /**
     * @param $IBAN
     * @param $BIC
     *
     * @return PaymentEntity $payment
     */
    public function createDebitNotePayment($IBAN, $BIC)
    {
        $payment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);

        $payment->setType(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_DEBITNOTE);

        if ($IBAN && $BIC) {
            $directDebit = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\BankAccountEntity::class);
            $directDebit->setBankAccount($IBAN);
            $directDebit->setBankCode($BIC);
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
     * @param $IBAN
     * @param $BIC
     * @param $selectedInstallmentPlanProfileId
     * @param $numberOfInstallments
     *
     * @return PaymentEntity
     */
    public function createInstallmentPayment(
        $IBAN,
        $BIC,
        $selectedInstallmentPlanProfileId,
        $numberOfInstallments
    ) {
        $payment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class);
        $payment->setType(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::TYPE_INSTALLMENT);

        if ($IBAN && $BIC) {
            $directDebit = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\BankAccountEntity::class);
            $directDebit->setBankAccount($IBAN);
            $directDebit->setBankCode($BIC);
            $payment->setDirectDebit($directDebit);
        }

        if ($selectedInstallmentPlanProfileId || $numberOfInstallments) {
            $installment = oxNew(\Arvato\AfterpayModule\Application\Model\Entity\Entity::class);
            $selectedInstallmentPlanProfileId && $installment->setProfileNo($selectedInstallmentPlanProfileId);
            $numberOfInstallments && $installment->setNumberOfInstallments($numberOfInstallments);
            $payment->setInstallment($installment);
        }
        return $payment;
    }
}
