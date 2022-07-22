<?php

/**
 * @codeCoverageIgnroeStart
 */

$sLangName = 'Deutsch';

$aLang = [
    'charset' => 'UTF-8',
    'ORDER_OVERVIEW_SALUTATION'                          => 'Anrede',
    'ORDER_OVERVIEW_APBIRTHDAY'                          => 'Geburtstag (tt.mm.jjjj)',
    'ORDER_OVERVIEW_APPHONE'                             => 'Telefon',
    'ORDER_OVERVIEW_APSSN'                               => 'SSN',
    'ORDER_OVERVIEW_FIRSTNAME'                           => 'Vorname',
    'ORDER_OVERVIEW_LASTNAME'                            => 'Nachname',
    'ORDER_OVERVIEW_STREET'                              => 'Straße',
    'ORDER_OVERVIEW_ZIP'                                 => 'PLZ',
    'ORDER_OVERVIEW_CITY'                                => 'Stadt',
    'MESSAGE_PAYMENT_BANK_ACCOUNT_INVALID'               => 'Bitte geben Sie eine gültige Bankverbindung an!',
    "MESSAGE_PAYMENT_SELECT_INSTALLMENT_PLAN"            => 'Ratenplan wählen:',
    "AFTERPAY_INSTALLMENT_BASKETAMOUNT"                  => 'Barzahlungspreis',
    "AFTERPAY_INSTALLMENT_NUMBEROFINSTALLMENTS"          => 'Anzahl Raten',
    "AFTERPAY_INSTALLMENT_INSTALLMENTAMOUNT"             => 'Monatliche Rate',
    "AFTERPAY_INSTALLMENT_FIRSTINSTALLMENTAMOUNT"        => 'Erste Rate',
    "AFTERPAY_INSTALLMENT_LASTINSTALLMENTAMOUNT"         => 'Letzte Rate',
    "AFTERPAY_INSTALLMENT_INTERESTRATE"                  => 'Sollzinssatz p.a.',
    "AFTERPAY_INSTALLMENT_EFFECTIVEINTERESTRATE"         => 'Effektiver Jahreszins',
    "AFTERPAY_INSTALLMENT_TOTALINTERESTAMOUNT"           => 'Summe der Zinsen',
    "AFTERPAY_INSTALLMENT_STARTUPFEE"                    => 'Einrichtungsgebühr',
    "AFTERPAY_INSTALLMENT_MONTHLYFEE"                    => 'Monatliche Gebühr',
    "AFTERPAY_INSTALLMENT_TOTALAMOUNT"                   => 'Gesamtbetrag',
    "AFTERPAY_INSTALLMENT_READMORE"                      => 'Rechtliche Angaben',
    "AFTERPAY_INSTALLMENT_IBAN"                          => 'Bankverbindung (IBAN)',
    "AFTERPAY_INSTALLMENT_TOTALINSTALLMENTCOST"          => 'Finanzierungskosten',
    "AFTERPAY_NO_INSTALLMENT"                            => 'Derzeit ist eine Ratenzahlung leider nicht verfügbar. Bitte wählen Sie eine andere Zahlungsart.',
    "AFTERPAY_LEGAL_INVOICE_DEBITNOTE"                   => 'Ich habe die <a href="##AGBLINK##" target="_new" style="text-decoration:underline">Allgemeinen Geschäftsbedingungen</a> und <a href="##PRIVACYLINK##" target="_new" style="text-decoration:underline">Datenschutzbestimmungen</a> von AfterPay gelesen und akzeptiere diese.',
    "AFTERPAY_LEGAL_INSTALLMENT"                         => '<hr>
                                                              <p>Bitte klicke <a href="##READMORELINK##" target="_new" style="text-decoration:underline">hier</a> für einen Tilgungsplan anhand beispielhafter Gesamtbeträge. Du musst keine Versicherung oder andere Sicherheiten stellen.</p>
                                                              <!--SECCISTART-->
                                                              <p>Europäische Standardinformationen für Verbraucherkredite (SECCI) <a href="##READMORELINK##" target="_new" style="text-decoration:underline">anzeigen</a></p>
                                                              <!--SECCIEND-->
                                                              <hr>
                                                              <p>
                                                              <input id="checkAfterPayAgbTop" type="checkbox" name="ord_afterpay_agb" value="1">
                                                              Ich habe die <a href="##AGBLINK##" target="_new" style="text-decoration:underline">Allgemeinen Geschäftsbedingungen</a> und <a href="##PRIVACYLINK##" target="_new" style="text-decoration:underline">Datenschutzbestimmungen</a> von AfterPay
                                                              sowie die sowie die <a href="##READMORELINK##" target="_new" style="text-decoration:underline">Besonderen Vertragsbedingungen für Ratenzahlungen/Teilzahlungsgeschäfte</a>
                                                              <!--SECCISTART-->
                                                              und die <a href="##READMORELINK##" target="_new" style="text-decoration:underline">Europäischen Standardinformationen für Verbraucherkredite (SECCI)</a>
                                                              <!--SECCIEND-->
                                                              gelesen und akzeptiere diese  .',
    'AFTERPAY__PAYMENTSELECT_LEGAL_DEBITNOTE'            => '<p><small>Mit meiner Bestellung ermächtige ich die Arvato Payment Solutions GmbH, Gläubiger-Identifikationsnummer DE23ZZZ00001986600, Zahlungen von meinem Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von dem Zahlungsempfänger auf mein Konto gezogenen Lastschriften einzulösen. Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.</small></p> <p>Die Belastung Ihres Kontos erfolgt mit dem Versand der Ware.</p>',
    'AFTERPAY__PAYMENTSELECT_LEGAL_INSTALLMENT'          => '<p><small>Mit meiner Bestellung ermächtige ich die Arvato Payment Solutions GmbH, Gläubiger-Identifikationsnummer DE23ZZZ00001986600, Zahlungen von meinem Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von dem Zahlungsempfänger auf mein Konto gezogenen Lastschriften einzulösen. Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.</small></p> <p>Der Gesamtbetrag könnte sich noch durch Versandkosten, Gutscheine etc. ändern. Die endgültigen Preise werden im letzten Bestellschritt angezeigt.</p>',
    'MESSAGE_USER_CHECK_CHANGED_ADDRESS'                 => 'Wir haben einen möglichen Fehler in Ihrer Adresse gefunden und korrigiert. Bitte überprüfen Sie Ihre Adresse.',
    'AFTERPAYOPTIN_PAYMENT_TEXT'                         => 'Um diese Zahlarten zu nutzen benötigen wir vorab Ihre Zustimmung.',
    'AFTERPAYOPTIN_PAYMENT_LABEL'                        => 'Ich stimme zu, dass meine Daten zum Zweck der Betrugsprävention gemäß Ziffer 4.1.2 der <a href="##PRIVACYLINK##">Datenschutzbestimmungen</a> verarbeitet werden.',
    'AFTERPAYOPTIN_PAYMENT_BUTTON'                       => 'Bestätigen',
    'AFTERPAY__PAYMENTSELECT_LEGAL_INSTALLMENT_ADDITION' => 'Hier findest du die <a target="_new" href="%s">Pflichtangaben nach § 507 BGB</a>,
                                                            die <a target="_new" class="AP_Installment_Info_Link" href="%s">Europäischen Standardinformationen für Verbraucherkredite</a> und die 
                                                            <a target="_new" href="%s">Datenschutzerklärung</a> der AfterPay Zahlart.
                                                            <br><br>Es gelten die <a target="_new" href="%s">Allgemeinen Geschäftsbedingungen</a> von AfterPay.',
    'AFTERPAY_THANKYOU_SHOPFIRST'                        => 'Kauf zuerst ein, zahle später mit AfterPay',
    'AFTERPAY_THANKYOU_TEXT'                             => 'Danke für Deinen Einkauf bei %s und dass Du AfterPay als Bezahlungsart ausgewählt hast. Du erhältst in Kürze eine E-Mail mit der Bestellbestätigung sowie Informationen zum Tracking, in der Zwischenzeit, sieh dir doch gerne an, was AfterPay für Dich tun kann.<br>
                                                            <a target="_new" href="https://www.myafterpay.com/de-de/feature/app">Lade unsere App</a>
                                                            oder besuche <a target="_new" href="https://www.myafterpay.com/">MyAfterPay</a>
                                                            im Browser, um herauszufinden, wie einfach du den Überblick über ausstehende Zahlungen behalten, Zahlungen für Rücksendungen pausieren kannst und vieles mehr.',
    'AFTERPAY__PAYMENTSELECT_INSTALLMENT_INFO'          => '* Bei einem Warenkob in Höhe von ##BASKETAMOUNT## € und ##NUMBEROFINSTALLMENTS## gleichbeleibenden Raten beläuft sich der Gesamtbetrag auf ##TOTALAMOUNT## €. Soll-Zinssatz ist ##INTERESTRATE## % und Effektivzinssatz ist ##EFFECTIVEINTERESTRATE## %.',
    'AFTERPAY__PAYMENTSELECT_INSTALLMENT_MONTH'         => 'Monat'
];
