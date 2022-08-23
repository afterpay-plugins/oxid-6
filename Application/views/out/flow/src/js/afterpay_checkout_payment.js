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
    NORISK_payment =
        {}
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
NORISK_payment.init =
    function () {
        var $afterpayOptin = $('.js-afterpay-optin')
        var $optinButton = $('.payment-afterpay-optin-action')

        if ($afterpayOptin.length) {
            $afterpayOptin.off('click', NORISK_payment.checkAfterpayOptin)
            $afterpayOptin.on('click', NORISK_payment.checkAfterpayOptin)
        }

        if ($optinButton.length) {
            $optinButton.off('click', NORISK_payment.submitAfterpayOptin)
            $optinButton.on('click', NORISK_payment.submitAfterpayOptin)
        }

        if ($('.paymenttypes-table').length) {
            var $paymentTypesInputs = $('.paymenttypes-table td input[type=\'radio\'][id^=\'payment_\']')

            $paymentTypesInputs.off('click', NORISK_payment.checkPaymentType)
            $paymentTypesInputs.on('click', NORISK_payment.checkPaymentType)
        }
    }

/* Functions
 * ============================================================================================================== */

/**
 * checkAfterpayOptin
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.checkAfterpayOptin =
    function () {
        var $this = $(this)
        var $optinWrapper = $this.closest('.payment-afterpay-optin-wrapper')
        var $optinButton = $optinWrapper.find('.payment-afterpay-optin-action')

        if ((this).checked) {
            $optinButton.removeAttr('disabled')
        } else {
            $optinButton.attr('disabled', 'disabled')
        }
    }

/**
 * submitAfterpayOptin
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.submitAfterpayOptin =
    function (e) {
        e.preventDefault()
        e.stopPropagation()

        var $this = $(this)
        var $afterpayWrapper = $this.closest('dl')
        var $optinWrapper = $afterpayWrapper.find('.payment-afterpay-optin-wrapper')
        var $contentWrapper = $afterpayWrapper.find('.afterpay_content')
        var $paymentTypeWrapper = $this.closest('td').find('input[type=\'radio\'][id^=\'payment_\']')
        var $continueBtn = $('.checkoutcontinuebtn button')

        $paymentTypeWrapper.addClass('approved')
        $continueBtn.removeAttr('disabled')

        console.log($optinWrapper);
        console.log($contentWrapper);

        $optinWrapper.addClass('hidden')
        $contentWrapper.removeClass('hidden')
    }

/**
 * checkPaymentType
 * -------------------------------------------------------------------------------------------------------------
 */
NORISK_payment.checkPaymentType =
    function () {
        var $this = $(this)
        var idValue = $this.attr('id')
        var $continueBtn = $('.checkoutcontinuebtn button')

        if (idValue.includes('afterpay') && !$this.hasClass('approved')) {
            $continueBtn.attr('disabled', 'disabled')
        } else {
            $continueBtn.removeAttr('disabled')
        }
    }

/* Initialization
 * ============================================================================================================== */
// Call when document is ready
$(document).ready(
    NORISK_payment.init
)

