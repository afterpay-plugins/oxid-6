[{ oxstyle include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/flow/src/css/afterpay.min.css') }]

<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
               [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b></label>
        <img src="https://cdn.myafterpay.com/logo/AfterPay_logo.svg" class="frontendAfterpayLogo">
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

            <p>[{oxmultilang ident="MESSAGE_PAYMENT_SELECT_INSTALLMENT_PLAN"}]</p>

            [{include file="wave/page/checkout/inc/order_installmentplan_boxes.tpl" aAvailableAfterpayInstallmentPlans=$aAvailableAfterpayInstallmentPlans afterpayInstallmentProfileId=$afterpayInstallmentProfileId}]

            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="afterpayinstallment_1">
                    *IBAN
                </label>
                <div class="col-lg-9">
                    <input id="afterpayinstallment_1" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apinstallmentbankaccount]" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="afterpayinstallment_2">
                    *BIC
                </label>
                <div class="col-lg-9">
                    <input id="afterpayinstallment_2" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apinstallmentbankcode]" value="">
                </div>
            </div>

            [{include file="wave/page/checkout/inc/afterpay_required_dynvalues.tpl" sPayment="Installments"}]


            <div style="clear:both"></div>

            <div class="alert alert-info offset-lg-3 desc">
                [{oxmultilang ident="AFTERPAY__PAYMENTSELECT_LEGAL_INSTALLMENT"}]
                [{if $paymentmethod->oxpayments__oxlongdesc->value|strip_tags|trim}]
                    [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                [{/if}]
            </div>

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
