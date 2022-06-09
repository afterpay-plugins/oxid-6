<div class="form-group">
    <div class="payment-afterpay-optin-wrapper">
        <div class="col-lg-3"></div>
        <div class="col-lg-9">
            [{if $trackingvalue == "mandatory"}]
                <p>[{oxmultilang ident="AFTERPAYOPTIN_PAYMENT_TEXT"}]</p>
            [{/if}]
            <label for="afterpay-optin-[{$sPaymentID}]">
                <input class="js-afterpay-optin" type="checkbox" value="1" name="AfterPayTrackingEnabled" id="afterpay-optin-[{$sPaymentID}]" />
                [{assign var="legal" value="AFTERPAYOPTIN_PAYMENT_LABEL"|oxmultilangassign}]
                [{assign var="legal" value=$legal|replace:"##PRIVACYLINK##":$PrivacyLink}]
                <span>[{$legal}]</span>
            </label>
            [{if $trackingvalue == "mandatory"}]
                <button disabled class="btn btn-primary payment-afterpay-optin-action">[{oxmultilang ident="AFTERPAYOPTIN_PAYMENT_BUTTON"}]</button>
            [{/if}]
        </div>
    </div>
</div>