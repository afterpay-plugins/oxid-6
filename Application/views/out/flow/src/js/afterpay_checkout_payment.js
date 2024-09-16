/**
 * Afterpay elements
 * ---------------------------------------------------------------------------------------------------------------
 * @copyright       ©2018 Noriskshop
 *
 * @author          Philipp Kolter <pkolter@noriskshop.de> - *MIL-274: Arvato-Afterpay-payment*
 * -------------------------------------------------------------------------------------------------------------- */

/* global NORISK_payment */

if (typeof NORISK_payment === 'undefined') {
    /* eslint-disable */
    /* OK:no-native-reassign */
    NORISK_payment = {};
    /* eslint-enable */
}

/* Variables
 * ============================================================================================================= */

/* Constructor
 * ============================================================================================================== */

/**
 * init
 * -----------------------------------------------------------------------------------------------------------------
 * init default filters
 *
 * Namespace: NOX_filter
 * @returns nil
 */
NORISK_payment.init = function () {
    var $afterpayOptin = $('.js-afterpay-optin');
    var $optinButton = $('.payment-afterpay-optin-action');
    var $nextStepButton = $("button.nextStep");

    if ($afterpayOptin.length) {
        $afterpayOptin.off('click', NORISK_payment.checkAfterpayOptin);
        $afterpayOptin.on('click', NORISK_payment.checkAfterpayOptin);
    }

    if ($optinButton.length) {
        $optinButton.off('click', NORISK_payment.submitAfterpayOptin);
        $optinButton.on('click', NORISK_payment.submitAfterpayOptin);
    }

    if ($('.paymenttypes-table').length) {
        var $paymentTypesInputs = $('.paymenttypes-table td input[type=\'radio\'][id^=\'payment_\']');

        $paymentTypesInputs.off('click', NORISK_payment.checkPaymentType);
        $paymentTypesInputs.on('click', NORISK_payment.checkPaymentType);
    }

    $nextStepButton.off('click', NORISK_payment.checkBirthdayField);
    $nextStepButton.on('click', NORISK_payment.checkBirthdayField);

    if ($(".afterpay_content input[id*='_bd']").length) {
        $(".afterpay_content input[id*='_bd']").off('keydown', NORISK_payment.inputOnlyNumbers);
        $(".afterpay_content input[id*='_bd']").on('keydown', NORISK_payment.inputOnlyNumbers);
    }

    $("#afterpayInvoice_bd").keyup(function (e) {
        if (e.keyCode != 8) {
            if ($(this).val().length == 2) {
                $(this).val($(this).val() + "/");
            }
            else if ($(this).val().length == 5) {
                $(this).val($(this).val() + "/");
            }
        }
    });
};

/* Functions
 * ============================================================================================================== */

/**
 * checkBirthdayField
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.inputOnlyNumbers = function (e) {
    if (!(e.metaKey || e.ctrlKey)) {
        if (!(e.keyCode < 59)) {
            if (!(/^[0-9]*\.?[0-9]*$/.test(e.key))) {
                e.preventDefault();
            }
        }
    }
};

/**
 * checkBirthdayField
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.checkBirthdayField = function (e) {
    e.preventDefault();
    if ($(".afterpay_content input[id*='_bd']:visible").length) {
        var birthdayField = $(".afterpay_content input[id*='_bd']:visible");
        /** DE with dots **/
        var regexDateDE = new RegExp(/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/);
        /** EN with slashes **/
        var regexDateEN = new RegExp(/^\s*(3[01]|[12][0-9]|0?[1-9])\/(1[012]|0?[1-9])\/((?:19|20)\d{2})\s*$/);
        var aBirthDate = birthdayField.val().split('.');
        if (!birthdayField.data('lang-locale').includes('de_')) {
            aBirthDate = birthdayField.val().split('/');
        }
        var bdYear = aBirthDate[2];
        var bdMonth = aBirthDate[1];
        var bdDay = aBirthDate[0];
        var cutOffDate = new Date(parseInt(bdYear) + 18, bdMonth, bdDay);
        var validLegalage = true;
        var blSubmit = true;
        if (cutOffDate > Date.now()) {
            validLegalage = false;
        }

        var validDateformat = regexDateDE.test(birthdayField.val());

        /** Slahes in EN **/
        if (!birthdayField.data('lang-locale').includes('de_')) {
            validDateformat = regexDateEN.test(birthdayField.val());
        }

        /** Day/Month/Year length **/
        if (bdDay.length !== 2 || bdMonth.length !== 2 || bdYear.length !== 4) {
            validDateformat = false;
            blSubmit = false;
        }

        /** validate legal age **/
        if (validDateformat && !validLegalage) {
            birthdayField.next().find('.date').hide();
            birthdayField.next().find('.age').show();
            blSubmit = false;
        }

        /** validate Date **/
        if (birthdayField.val() === null && birthdayField.val() === "" || !validDateformat || (bdMonth == "02" && bdDay > "29")) {
            birthdayField.next().find('.age').hide();
            birthdayField.next().find('.date').show();
            blSubmit = false;
        }

        if (blSubmit) {
            $(this).closest('form').submit();
        }
    }
    else {
        $(this).closest('form').submit();
    }
};

/**
 * checkAfterpayOptin
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.checkAfterpayOptin = function () {
    var $this = $(this);
    var $optinWrapper = $this.closest('.payment-afterpay-optin-wrapper');
    var $optinButton = $optinWrapper.find('.payment-afterpay-optin-action');

    if ((this).checked) {
        $optinButton.removeAttr('disabled');
    }
    else {
        $optinButton.attr('disabled', 'disabled');
    }
};

/**
 * submitAfterpayOptin
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.submitAfterpayOptin = function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $this = $(this);
    var $afterpayWrapper = $this.closest('dl');
    var $optinWrapper = $afterpayWrapper.find('.payment-afterpay-optin-wrapper');
    var $contentWrapper = $afterpayWrapper.find('.afterpay_content');
    var $paymentTypeWrapper = $this.closest('td').find('input[type=\'radio\'][id^=\'payment_\']');
    var $continueBtn = $('.checkoutcontinuebtn button');

    $paymentTypeWrapper.addClass('approved');
    $continueBtn.removeAttr('disabled');

    console.log($optinWrapper);
    console.log($contentWrapper);

    $optinWrapper.addClass('hidden');
    $contentWrapper.removeClass('hidden');
};

/**
 * checkPaymentType
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.checkPaymentType = function () {
    var $this = $(this);
    var idValue = $this.attr('id');
    var $continueBtn = $('.checkoutcontinuebtn button');

    if (idValue.includes('afterpay') && !$this.hasClass('approved')) {
        $continueBtn.attr('disabled', 'disabled');
    }
    else {
        $continueBtn.removeAttr('disabled');
    }
};

/* Initialization
 * ============================================================================================================== */
// Call when document is ready
$(document).ready(
    NORISK_payment.init
);

