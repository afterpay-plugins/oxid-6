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

        [{if $aAvailableAfterpayInstallmentPlans}]
        <div class="afterpay_content">
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="afterpayinstallment_1">
                    [{oxmultilang ident="AFTERPAY_INSTALLMENT_IBAN"}]
                </label>
                <div class="col-lg-9">
                    <input id="afterpayinstallment_1" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apinstallmentbankaccount]" value="" required>
                </div>
            </div>

            [{include file="flow/page/checkout/inc/afterpay_required_dynvalues.tpl" sPayment="Installments"}]
            <p>[{oxmultilang ident="MESSAGE_PAYMENT_SELECT_INSTALLMENT_PLAN"}]</p>
            [{include file="flow/page/checkout/inc/order_installmentplan_boxes.tpl" aAvailableAfterpayInstallmentPlans=$aAvailableAfterpayInstallmentPlans afterpayInstallmentProfileId=$afterpayInstallmentProfileId}]

        </div>

        <div style="clear:both"></div>

        [{if $trackingvalue != "inactive"}]
        [{include file="flow/page/checkout/inc/payment_tracking.tpl"}]
        [{/if}]

        [{else}]
            [{block name="checkout_payment_longdesc"}]
                [{if $paymentmethod->oxpayments__oxlongdesc->value|strip_tags|trim}]
                    <div class="alert alert-info offset-lg-3 desc">
                        [{oxmultilang ident="AFTERPAY_NO_INSTALLMENT"}]
                    </div>
                [{/if}]
            [{/block}]
        [{/if}]

    </dd>
</dl>
