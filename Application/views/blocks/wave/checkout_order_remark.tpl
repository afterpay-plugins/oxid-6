[{$smarty.block.parent}]
[{ oxstyle include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/wave/src/css/afterpay.min.css') }]
[{ oxscript include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/wave/src/js/afterpay_checkout_order.min.js') }]

[{assign var="payment" value=$oView->getPayment()}]

[{if $payment->oxpayments__oxid->value|strstr:"afterpay"}]

    <div class="card-wrapper">
        <div class="card">
            <h3 class="card-header">
                <img src="https://cdn.myafterpay.com/logo/AfterPay_logo.svg" class="frontendAfterpayLogoAsTitle">
            </h3>
        </div>
        <div class="card-body">

            [{if $payment->oxpayments__oxid->value eq "afterpayinstallment"}]

                <div>
                    [{include file="wave/page/checkout/inc/order_installmentplan_boxes.tpl" aAvailableAfterpayInstallmentPlans=$aAvailableAfterpayInstallmentPlans afterpayInstallmentProfileId=$afterpayInstallmentProfileId finalOrderStep=true}]
                </div>
                <div style="clear:both"></div>
                [{assign var="legal" value="AFTERPAY_LEGAL_INSTALLMENT"|oxmultilangassign}]
                [{assign var="legal" value=$legal|replace:"##READMORELINK##":$afterpayReadMoreLink}]
                [{if !$afterpayShowSecci}]
                    [{assign var="legal" value=$legal|replace:"<!--SECCISTART-->":'<!--SECCISTART '}]
                    [{assign var="legal" value=$legal|replace:"<!--SECCIEND-->":' SECCIEND-->'}]
                [{/if}]
                [{$legal}]

            [{elseif $payment->oxpayments__oxid->value eq "afterpaydebitnote"}]
                [{oxmultilang ident="AFTERPAY_LEGAL_INVOICE_DEBITNOTE"}]
            [{elseif $payment->oxpayments__oxid->value eq "afterpayinvoice"}]
                [{oxmultilang ident="AFTERPAY_LEGAL_INVOICE_DEBITNOTE"}]
            [{/if}]

        </div>
    </div>

[{/if}]
