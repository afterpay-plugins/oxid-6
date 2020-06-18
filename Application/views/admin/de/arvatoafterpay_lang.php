<?php

$sLangName = 'Deutsch';

$aLang = [
    'charset' => 'UTF-8',

    'ORDER_OVERVIEW_APBIRTHDAY' => 'Geburtstag',
    'ORDER_OVERVIEW_APPHONE'    => 'Telefon',
    'ORDER_OVERVIEW_APSSN'      => 'SSN',

    'ORDER_AFTERPAY_ORDERED_AMOUNT'  => 'Bestellte Anzahl',
    'ORDER_AFTERPAY_CAPTURED_AMOUNT' => 'Davon eingezogene Anzahl',
    'ORDER_AFTERPAY_REFUNDED_AMOUNT' => 'Davon erstattete Anzahl',
    'ORDER_AFTERPAY_VOIDED_AMOUNT'   => 'Davon stornierte Anzahl',

    'ORDER_AFTERPAY_TITLE_LISTOFALLITEMS'    => 'Gesamtliste aller bestellter Artikel',
    'ORDER_AFTERPAY_TITLE_CAPTUREPAYMENT'    => 'Reservierte Zahlung einziehen',
    'ORDER_AFTERPAY_TITLE_REFUNDPAYMENT'     => 'Eingezogene Zahlung erstatten',
    'ORDER_AFTERPAY_TITLE_REFUNDPAYMENTFREE' => 'Eingezogene Zahlung erstatten - Frei wählbarer Betrag',
    'ORDER_AFTERPAY_TITLE_VOIDPAYMENT'       => 'Reservierte Zahlung freigeben',

    'SHOP_MODULE_GROUP_arvatoAfterpayGeneral'               => 'Allgemeine Einstellungen',
    'SHOP_MODULE_arvatoAfterpayApiDefaultShippingCompany'   => 'Standard Versandunternehmen',
    'SHOP_MODULE_arvatoAfterpayApiDefaultRefundDescription' => 'Standard Rückerstattungs-Beschreibung',
    'SHOP_MODULE_arvatoAfterpayRiskChannelType'             => 'Risikosteuerung - ChanelType',
    'SHOP_MODULE_arvatoAfterpayRiskDeliveryType'            => 'Risikosteuerung - DeliveryType',

    'SHOP_MODULE_GROUP_arvatoAfterpayApi' => 'API Einstellungen',
    'SHOP_MODULE_arvatoAfterpayApiUrl'    => 'API Url',

    'SHOP_MODULE_arvatoAfterpayApiKeyDE'            => 'API Schlüssel DE',
    'SHOP_MODULE_arvatoAfterpayApiKeyDEInstallment' => 'API Schlüssel für Ratenkauf DE',
    'SHOP_MODULE_arvatoAfterpayApiKeyAT'            => 'API Schlüssel AT',
    'SHOP_MODULE_arvatoAfterpayApiKeyATInstallment' => 'API Schlüssel für Ratenkauf AT',
    'SHOP_MODULE_arvatoAfterpayApiKeyCH'            => 'API Schlüssel CH',
    'SHOP_MODULE_arvatoAfterpayApiKeyNL'            => 'API Schlüssel NL',

    'SHOP_MODULE_arvatoAfterpayApiSandboxMode' => 'Sandbox Modus',
    'SHOP_MODULE_arvatoAfterpayApiSandboxUrl'  => 'Sandbox API URL',

    'SHOP_MODULE_arvatoAfterpayApiSandboxKeyDE'            => 'Sandbox API Schlüssel DE',
    'SHOP_MODULE_arvatoAfterpayApiSandboxKeyDEInstallment' => 'Sandbox API Schlüssel für Ratenkauf DE',
    'SHOP_MODULE_arvatoAfterpayApiSandboxKeyAT'            => 'Sandbox API Schlüssel AT',
    'SHOP_MODULE_arvatoAfterpayApiSandboxKeyATInstallment' => 'Sandbox API Schlüssel für Ratenkauf AT',
    'SHOP_MODULE_arvatoAfterpayApiSandboxKeyCH'            => 'Sandbox API Schlüssel CH',
    'SHOP_MODULE_arvatoAfterpayApiSandboxKeyNL'            => 'Sandbox API Schlüssel NL',

    'SHOP_MODULE_arvatoAfterpayApiRequestLogging'      => 'Api Anfragenprotokollierung',
    'arvato_afterpay_controllers_admin_order_afterpay' => 'AfterPay',
    'AFTERPAY_ONLY_FOR_AFTERPAY_PAYMENT'               => 'Diese Bestellung wurde nicht mit AfterPay bezahlt',
    'ORDER_OVERVIEW_APDEBITBANKACCOUNT'                => 'IBAN',
    'ORDER_OVERVIEW_APDEBITBANKCODE'                   => 'BIC',
    'ORDER_OVERVIEW_APINSTALLMENTBANKACCOUNT'          => 'IBAN',
    'ORDER_OVERVIEW_APINSTALLMENTBANKCODE'             => 'BIC',
    'ORDER_OVERVIEW_AFTERPAYINSTALLMENTPROFILEID'      => 'Ratenzahlungsprofil',

    'SHOP_MODULE_GROUP_arvatoAfterpayRequiredFields'          => 'Pflichtfelder',
    'SHOP_MODULE_arvatoAfterpayInvoiceRequiresBirthdate'      => 'Rechnung erfordert Geburtsdatum',
    'SHOP_MODULE_arvatoAfterpayDebitRequiresBirthdate'        => 'Bankeinzug erfordert Geburtsdatum',
    'SHOP_MODULE_arvatoAfterpayInstallmentsRequiresBirthdate' => 'Ratenzahlung erfordert Geburtsdatum',
    'SHOP_MODULE_arvatoAfterpayInvoiceRequiresSSN'            => 'Rechnung erfordert SSN',
    'SHOP_MODULE_arvatoAfterpayDebitRequiresSSN'              => 'Bankeinzug erfordert SSN',
    'SHOP_MODULE_arvatoAfterpayInstallmentsRequiresSSN'       => 'Ratenzahlung erfordert SSN',
    'SHOP_MODULE_arvatoAfterpayInvoiceRequiresFon'            => 'Rechnung erfordert Telefonnummer',
    'SHOP_MODULE_arvatoAfterpayDebitRequiresFon'              => 'Bankeinzug erfordert Telefonnummer',
    'SHOP_MODULE_arvatoAfterpayInstallmentsRequiresFon'       => 'Ratenzahlung erfordert Telefonnummer',

    'SHOP_MODULE_GROUP_arvatoAfterpayProfileTracking'  => 'Profile Tracking (Browsergestützte Betrugserkennung)',
    'SHOP_MODULE_arvatoAfterpayProfileTrackingEnabled' => 'Profile Tracking aktivieren',
    'SHOP_MODULE_arvatoAfterpayProfileTrackingUrl'     => 'Profile Tracking URL',
    'SHOP_MODULE_arvatoAfterpayProfileTrackingId'      => 'Profile Tracking Client ID',

    'HELP_ARTICLE_MAIN_AAPPRODUCTGROUP' => 'Definieren Sie die AfterPay-Produktgruppe des Artikels. Lassen Sie das Feld leer, so wird versucht die Produktgruppe anhand der zugeordneten Kategorien zu beziehen. In Klammern steht die übertragene Produktgruppe.',
    'ORDER_AFTERPAY_FORCAPTURENO'       => 'auf Capture-Nummer',
    'ORDER_AFTERPAY_PROMPT_REALLYVOID'     => 'Reservierung freigeben? Das kann nicht rückgängig gemacht werden. Die Bestellung kann danach nicht weiter bearbeitet werden.',
    'ORDER_AFTERPAY_PROMPT_REALLYREFUND'   => 'Zahlung erstatten? Das kann nicht rückgängig gemacht werden.',
    'ORDER_AFTERPAY_TITLE_CAPTUREDELIVERY' => 'Versand erfassen',

    'AFTERPAY_ORDER_ALREADY_VOIDED' => 'Diese Bestellung enthält stornierte Zahlungen und kann deshalb nicht weiter bearbeitet werden.',
];
