# OXID ArvatoAfterPay Module

# Version 3.0.0

## Description

* First standalone release of the Arvato AfterPay Module
* Supports 3 payment types debit, invoice, installments
* Supports handling over the oxid admin backend only

## Installation

Use Composer to add the module to your project
```bash
composer require arvato/afterpay-oxid-module
```

* Activate the module in administration area
* clear tmp and regenerate views
* Make sure to take care of all the settings, options and credentials described in the user manual

## Uninstall

* Deactivate the module in administration area
* remove "arvato/afterpay-oxid-module" from your composer.json

Run Composer again to remove Module from vendor
```bash
composer update
```

## Changelog

### Version 1.0.0

* Version for OXID4 installable via FTP

### Version 2.0.0

* Version for OXID6 installable via Composer

### Version 2.1.0

* APM-17 Add configuration options for brand and variant
* APM-22 Support for belgium
* APM-23 Save order on afterpay request

### Version 2.1.1

* APM-26 Change Sandbox URL
* APM-28 Renaming in Composer
* APM-33 Replace logo in module
* APM-38 Packagist naming for Oxid plugin

### Version 3.0.0

* APM-51 Exchange "Secci" link
* APM-46 Mandatory fields configurable by country
* APM-45 Instalments Checkout Improvements for DACH (Germany and Austria)
* APM-42 Removal of BIC for Debit
* APM-36 Admin Checkout - Payment Info Display
* APM-34 Configuration on own page
* APM-31 Removal of BIC for payment by instalments
* APM-30 Exclusion Afterpay for certain items (cash vouchers)
* APM-24 Optin Profile Tracking