<?php

$sLangName = 'English';

$aLang = [
    'charset'                                            => 'UTF-8',
    'ORDER_OVERVIEW_SALUTATION'                          => 'Salutation',
    'ORDER_OVERVIEW_APBIRTHDAY'                          => 'Birthdate (dd/mm/yyyy)',
    'ORDER_OVERVIEW_APBIRTHDAY_ERROR_AGE'                => 'You must be at least 18 years old.',
    'ORDER_OVERVIEW_APBIRTHDAY_ERROR_DATE'               => 'Please enter a valid date (dd/mm/yyyy)',
    'ORDER_OVERVIEW_APPHONE'                             => 'Phone',
    'ORDER_OVERVIEW_APSSN'                               => 'SSN',
    'MESSAGE_PAYMENT_BANK_ACCOUNT_INVALID'               => 'Please provide a valid account!',
    "MESSAGE_PAYMENT_SELECT_INSTALLMENT_PLAN"            => 'Please select an installments plan.',
    "AFTERPAY_INSTALLMENT_BASKETAMOUNT"                  => 'Basket amount',
    "AFTERPAY_INSTALLMENT_NUMBEROFINSTALLMENTS"          => 'Number of installments',
    "AFTERPAY_INSTALLMENT_INSTALLMENTAMOUNT"             => 'Monthly installment amount',
    "AFTERPAY_INSTALLMENT_FIRSTINSTALLMENTAMOUNT"        => 'First installment amount',
    "AFTERPAY_INSTALLMENT_LASTINSTALLMENTAMOUNT"         => 'Last installment amount',
    "AFTERPAY_INSTALLMENT_INTERESTRATE"                  => 'Nominal annual interest rate',
    "AFTERPAY_INSTALLMENT_EFFECTIVEINTERESTRATE"         => 'Effective Annual interest rate',
    "AFTERPAY_INSTALLMENT_TOTALINTERESTAMOUNT"           => 'Total interest amount',
    "AFTERPAY_INSTALLMENT_STARTUPFEE"                    => 'Startup fee',
    "AFTERPAY_INSTALLMENT_MONTHLYFEE"                    => 'Monthly fee',
    "AFTERPAY_INSTALLMENT_TOTALAMOUNT"                   => 'Total amount',
    "AFTERPAY_INSTALLMENT_READMORE"                      => 'Legal documents',
    "AFTERPAY_INSTALLMENT_IBAN"                          => 'Bank details (IBAN)',
    "AFTERPAY_INSTALLMENT_TOTALINSTALLMENTCOST"          => 'Total financing costs',
    "AFTERPAY_NO_INSTALLMENT"                            => 'We apologize for not being able to provide any installments plans right now. Please select another payment method.',
    "AFTERPAY_LEGAL_INVOICE_DEBITNOTE"                   => 'I have read and accept the <a href="##AGBLINK##"  target="_new" style="text-decoration:underline">Terms & Conditions</a> and <a href="##PRIVACYLINK##" target="_new" style="text-decoration:underline">data protection regulations</a> of AfterPay.',
    "AFTERPAY_LEGAL_INSTALLMENT"                         => '<hr>
                                                      <p>Please click <a href="##READMORELINK##" target="_new" style="text-decoration:underline">here</a>  for a repayment plan based on example totals. You do not have to provide insurance or other security.</p>
                                                      <!--SECCISTART-->
                                                      <p>Standard European Consumer Credit Information (SECCI) <a href="##READMORELINK##" target="_new" style="text-decoration:underline">show</a>.</p>
                                                      <!--SECCIEND-->
                                                      <hr>
                                                      <p>I have read and accept the <a href="##AGBLINK##" target="_new" style="text-decoration:underline">Terms & Conditions</a> and <a href="##PRIVACYLINK##" target="_new" style="text-decoration:underline">data protection regulations</a> of AfterPay.
                                                      <p>For the part payments I have also read the
                                                        <a href="##READMORELINK##" target="_new" style="text-decoration:underline">Special Terms & Conditions for installments</a>
                                                        <!--SECCISTART-->
                                                        and the <a href="##READMORELINK##" target="_new" style="text-decoration:underline">Standard European Consumer Credit Information (SECCI)</a>
                                                        <!--SECCIEND-->
                                                       .
                                                      </p>',
    'AFTERPAY__PAYMENTSELECT_LEGAL_DEBITNOTE'            => '<p><small>With my order, I authorize Arvato Payment Solutions GmbH, creditor identification number DE23ZZZ00001986600, to collect payments from my account by direct debit. At the same time, I instruct my bank to redeem the direct debits drawn by the payee into my account. Note: I can claim reimbursement of the amount due within eight weeks of the debit date. Applicable in this regard by the contract with my bank conditions.</small></p> <p>Your bank account will be charged when the order is shipped.</p>',
    'AFTERPAY__PAYMENTSELECT_LEGAL_INSTALLMENT'          => '<p><small>With my order, I authorize Arvato Payment Solutions GmbH, creditor identification number DE23ZZZ00001986600, to collect payments from my account by direct debit. At the same time, I instruct my bank to redeem the direct debits drawn by the payee into my account. Note: I can claim reimbursement of the amount due within eight weeks of the debit date. Applicable in this regard by the contract with my bank conditions.</small></p> <p>The amount could be changed, e.g. by shipping, handling and vouchers. We will display the exact price in the final checkout step.</p>',
    'MESSAGE_USER_CHECK_CHANGED_ADDRESS'                 => 'We found and corrected a possible mistake in your address. Please check.',
    'AFTERPAYOPTIN_PAYMENT_TEXT'                         => 'To use these payment methods we need your consent in advance.',
    'AFTERPAYOPTIN_PAYMENT_LABEL'                        => 'I agree to the use of my data for the purpose of fraud prevention according to clause 4.1.2 of the <a href="##PRIVACYLINK##">Privacy Policy</a>.',
    'AFTERPAYOPTIN_PAYMENT_BUTTON'                       => 'Confirm',
    'AFTERPAY__PAYMENTSELECT_LEGAL_INSTALLMENT_ADDITION' => 'Here you will find the <a target="_new" href="%s">mandatory information</a> according to § 507 BGB (German Civil Code), the <a target="_new" class="AP_Installment_Info_Link" href="%s">European Standard Information for Consumer Credit</a> and the <a target="_new" href="%s">Privacy Policy</a> of the AfterPay payment method. The <a target="_new" href="%s">General Terms and Conditions for AfterPay</a> apply.',
    'AFTERPAY_THANKYOU_SHOPFIRST'                        => 'shop first, pay later with AfterPay',
    'AFTERPAY_THANKYOU_TEXT'                             => 'Thank you for your purchase at %s and for choosing AfterPay as your payment method. An email with the order confirmation, details and tracking info is on the way, but feel free to discover what AfterPay can do for you.<br>
                                                            <a target="_new" href="https://www.myafterpay.com/en-de/feature/app">Download our app</a>
                                                            on your smartphone or visit <a target="_new" href="https://www.myafterpay.com/">MyAfterPay</a>
                                                            in your browser to find out the easiest way to keep track of outstanding payments, pause payments for returns or keep track and many more options.',
    'AFTERPAY__PAYMENTSELECT_INSTALLMENT_INFO'          => '* The shopping cart of ##BASKETAMOUNT## € results in a total loan amount of ##TOTALAMOUNT## € when selecting ##NUMBEROFINSTALLMENTS## installments. Debit interest is ##INTERESTRATE## % and effective interest rate is ##EFFECTIVEINTERESTRATE## %.',
    'AFTERPAY__PAYMENTSELECT_INSTALLMENT_MONTH'         => 'Month',
    'AFTERPAY__PAYMENTSELECT_INSTALLMENT_NUMBER'        => 'for ##NUMBEROFINSTALLMENTS## installments',
    'AFTERPAY__PAYMENTSELECT_TCPRIVICY'                 => 'The general <a target="_new" href="##AGBLINK##">Terms and Conditions</a> for the AfterPay payment method apply. The privacy policy of AfterPay can be found <a target="_new" href="##PRIVACYLINK##">here</a>'
];
