<div class="form-group">
    <div class="payment-afterpay-optin-wrapper">
        <div class="col-lg-3"></div>
        <div class="col-lg-9">
            <label for="afterpay-optin-[{$sPaymentID}]">
                <input class="js-afterpay-optin" type="checkbox" value="1" name="AfterPayTrackingEnabled" id="afterpay-optin-[{$sPaymentID}]" />
                [{assign var="legal" value="AFTERPAYOPTIN_PAYMENT_LABEL"|oxmultilangassign}]
                [{assign var="legal" value=$legal|replace:"##PRIVACYLINK##":$PrivacyLink}]
                <span>[{$legal}]</span>
            </label>
        </div>
    </div>
</div>