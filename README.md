# OXID PS ArvatoAfterPay Module

# Version 2.0.0

## Description

 * First standalone release of the Arvato AfterPay Module
 * Supports 3 payment types debit, invoice, installments
 * Supports handling over the oxid admin backend only

## Installation

Use Composer to add the module to your project
```bash
composer require arvato/afterpay
```

 * Activate the module in administration area
 * clear tmp and regenerate views
 * Make sure to take care of all the settings, options and credentials described in the user manual

## Uninstall

 * Deactivate the module in administration area
 * remove "arvato/afterpay" from your composer.json

Run Composer again to remove Module from vendor
```bash
composer update
```

## Changelog

### Version 1.0.0

* Version for OXID4 installable via FTP

### Version 2.0.0

* Version for OXID6 installable via Composer
