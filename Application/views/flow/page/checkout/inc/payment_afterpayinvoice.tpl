<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
               [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b></label>
        <img src="[{$oViewConf->getAfterpayLogoUrl()}]" class="frontendAfterpayLogo">
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        [{if $paymentmethod->getPrice()}]
            [{assign var="oPaymentPrice" value=$paymentmethod->getPrice() }]
            [{if $oViewConf->isFunctionalityEnabled('blShowVATForPayCharge') }]
                [{strip}]
                    ([{oxprice price=$oPaymentPrice->getNettoPrice() currency=$currency}]
                    [{if $oPaymentPrice->getVatValue() > 0}]
                        [{oxmultilang ident="PLUS_VAT"}] [{oxprice price=$oPaymentPrice->getVatValue() currency=$currency}]
                    [{/if}])
                [{/strip}]
            [{else}]
                ([{oxprice price=$oPaymentPrice->getBruttoPrice() currency=$currency}])
            [{/if}]
        [{/if}]

        <div class="afterpay_content">
            [{include file="flow/page/checkout/inc/afterpay_required_dynvalues.tpl" sPayment="Invoice"}]
        </div>

        <div class="form-group">
            <div class="col-lg-3"></div>
            <div class="col-lg-9">
                [{assign var="tcp" value="AFTERPAY__PAYMENTSELECT_TCPRIVICY"|oxmultilangassign}]
                [{assign var="AGBLink" value=$AGBLink|replace:"##PAYMENT##":"Invoice"}]
                [{assign var="tcp" value=$tcp|replace:"##AGBLINK##":$AGBLink}]
                [{assign var="tcp" value=$tcp|replace:"##PRIVACYLINK##":$PrivacyLink}]
                <span>[{$tcp}]</span>
            </div>
        </div>

        [{if $trackingvalue != "inactive"}]
            [{include file="flow/page/checkout/inc/payment_tracking.tpl"}]
        [{/if}]

        <div style="clear:both"></div>
        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value|strip_tags|trim}]
                <div class="alert alert-info col-lg-offset-3 desc">
                    [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                </div>
            [{/if}]
        [{/block}]
    </dd>
</dl>
