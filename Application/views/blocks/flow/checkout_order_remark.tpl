[{$smarty.block.parent}]
[{ oxstyle include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/flow/src/css/afterpay.min.css') }]
[{ oxscript include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/flow/src/js/afterpay_checkout_order.min.js') }]

[{assign var="payment" value=$oView->getPayment()}]

[{if $payment->oxpayments__oxid->value|strstr:"afterpay"}]

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <img src="[{$oViewConf->getAfterpayLogoUrl()}]" class="frontendAfterpayLogoAsTitle">
            </h3>
        </div>
        <div class="panel-body">

            [{if $payment->oxpayments__oxid->value eq "afterpayinstallment"}]

                <div>
                    [{include file="flow/page/checkout/inc/order_installmentplan_boxes.tpl" aAvailableAfterpayInstallmentPlans=$aAvailableAfterpayInstallmentPlans afterpayInstallmentProfileId=$afterpayInstallmentProfileId finalOrderStep=true}]
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
