[{ oxstyle include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/flow/src/css/afterpay.min.css') }]

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
            <div class="form-group">
                <label class="control-label col-lg-3" for="afterpaydebitnote_1">
                    *IBAN
                </label>
                <div class="col-lg-9">
                    <input id="afterpaydebitnote_1" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apdebitbankaccount]" value="" aria-invalid="false">
                </div>
            </div>

            [{include file="flow/page/checkout/inc/afterpay_required_dynvalues.tpl" sPayment="Debit"}]

            [{if $trackingvalue != "inactive"}]
                [{include file="flow/page/checkout/inc/payment_tracking.tpl"}]
            [{/if}]
        </div>
    </dd>
</dl>
