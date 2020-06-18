[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{oxscript include="js/libs/jquery.min.js"}]
[{oxscript add="jQuery.noConflict();" priority=10}]

<script type="text/javascript">
    window.onload = function () {
        top.oxid.admin.updateList('[{$sOxid}]')
    };
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_overview">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<img src="https://cdn.myafterpay.com/logo/AfterPay_logo.svg" class="frontendAfterpayLogo" style="max-height: 32px; max-width:170px; padding:8px; float: right;">


[{if $oOrder}]

    [{*
    ######################
        MESSAGES
    ######################
    *}]

    [{if $sMessage}]
        <div class="messagebox">[{$sMessage}]</div>
    [{/if}]

    [{if $aErrorMessages}]
        [{foreach from=$aErrorMessages item="sErrorMessage"}]
            <div class="errorbox">[{$sErrorMessage}]</div>
        [{/foreach}]
    [{/if}]

    [{if $oCaptureSuccess}]
        <div class="messagebox">
            <b>OK - Captured [{$oCaptureSuccess->getCapturedAmount()|string_format:"%.2f"}] [{$oOrder->oxorder__oxcurrency}]</b>
            with capture number [{$oCaptureSuccess->getCaptureNumber()}].<br>
        </div>
    [{/if}]

    [{if $bVoidSuccess}]
        <div class="messagebox">
            <b>OK.</b><br>
        </div>
    [{/if}]

    [{if $oCaptureShippingSuccess}]
        <div class="messagebox">
            <b>OK - Recorded Shipping</b> No. [{$oCaptureShippingSuccess->getShippingNumber()}] for this order.<br>
        </div>
    [{/if}]

    [{if $oRefundSuccess}]
        <div class="messagebox">
            <b>OK - Refund successfull.</b> Refund No. [{', '|implode:$oRefundSuccess->getRefundNumbers()}].<br>
        </div>
    [{/if}]

    [{*
    ######################
        FORMS
    ######################
    *}]

    [{assign var="oAfterpayOrder" value=$oOrder->getAfterpayOrder()}]

    [{if $aArvatoAllOrderItems}]
        <h2>[{oxmultilang ident="ORDER_AFTERPAY_TITLE_LISTOFALLITEMS"}]</h2>
        [{include file="order_afterpay_item.tpl" items=$aArvatoAllOrderItems form="list" allItems=$aArvatoAllOrderItems capturedItems=$aArvatoCapturedOrderItems refundedItems=$aArvatoRefundedOrderItems}]
    [{/if}]


    [{if $aArvatoOrderItemsCanCaptureOrVoid}]
        <h2>[{oxmultilang ident="ORDER_AFTERPAY_TITLE_CAPTUREPAYMENT"}]</h2>
        [{include file="order_afterpay_item.tpl" items=$aArvatoOrderItemsCanCaptureOrVoid form="capture" allItems=$aArvatoAllOrderItems capturedItems=$aArvatoCapturedOrderItems refundedItems=$aArvatoRefundedOrderItems}]
    [{/if}]

    [{if $aArvatoOrderItemsCanCaptureOrVoid}]
        <h2>[{oxmultilang ident="ORDER_AFTERPAY_TITLE_VOIDPAYMENT"}]</h2>
        [{include file="order_afterpay_item.tpl" items=$aArvatoOrderItemsCanCaptureOrVoid form="void" allItems=$aArvatoAllOrderItems capturedItems=$aArvatoCapturedOrderItems refundedItems=$aArvatoRefundedOrderItems}]
    [{/if}]

    [{if $aArvatoOrderItemsCanRefund}]
        <h2>[{oxmultilang ident="ORDER_AFTERPAY_TITLE_REFUNDPAYMENT"}]</h2>
        [{foreach from=$aArvatoOrderItemsCanRefund item=aArvatoOrderItemsCanRefundByCapture}]
            [{include file="order_afterpay_item.tpl" items=$aArvatoOrderItemsCanRefundByCapture->captureItems form="refund" allItems=$aArvatoAllOrderItems captureMeta=$aArvatoOrderItemsCanRefundByCapture}]
        [{/foreach}]
    [{/if}]



    [{if $bArvatoCanFreeRefund}]

        <h2>[{oxmultilang ident="ORDER_AFTERPAY_TITLE_REFUNDPAYMENTFREE"}]</h2>
        <form name="myedit2" action="[{$oViewConf->getSelfLink()}]" method="get" style="border:1px solid grey">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="orderafterpay">
            <input type="hidden" name="fnc" value="refund">
            <input type="hidden" name="oxid" value="[{$oxid}]">
            [{assign var='vats' value=','|explode:"1,2,3"}]
            [{foreach from=$oView->getRefundVatPercentages() item="vat" key="cnt"}]
                <fieldset>
                    <span style="color:grey">
                    *ProductId:<input type="text" name="refunds[[{$cnt}]][productId]" value="1" style="width: 3em;">
                    *groupId:<input type="text" name="refunds[[{$cnt}]][groupId]" value="01" style="width: 3em;">
                    *[{oxmultilang ident="USER_ARTICLE_QUANTITY" }]:<input type="text" name="refunds[[{$cnt}]][quantity]" value="1" style="width: 3em;">
                    *[{oxmultilang ident="SHOP_OPTIONS_GROUP_VAT" }]:<input type="text" name="refunds[[{$cnt}]][vatPercent]" value="[{$vat}]" style="width: 3em;">%<br>
                    </span>
                    *[{oxmultilang ident="GENERAL_DESCRIPTION" }]:<input type="text" name="refunds[[{$cnt}]][description]" value="[{$oView->getDefaultRefundDescription()}]"  style="width: 20em;"><br>
                    *[{oxmultilang ident="GENERAL_ARTICLE_OXBPRICE" }]<input type="text" name="refunds[[{$cnt}]][grossUnitPrice]"> [{$oOrder->oxorder__oxcurrency}]<br>
                </fieldset>
            [{/foreach}]
            <button id="refundButton" class="actionLink" href="#">
                Refund
            </button>
        </form>
    [{/if}]

    [{if $oAfterpayOrder->getStatus() eq 'captured'}]
        <h2>[{oxmultilang ident="ORDER_AFTERPAY_TITLE_CAPTUREDELIVERY"}]</h2>
        <form name="myedit" action="[{$oViewConf->getSelfLink()}]" method="get">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="orderafterpay">
            <input type="hidden" name="fnc" value="captureshipping">
            <input type="hidden" name="oxid" value="[{$oxid}]">
            *Track Code:<input type="text" name="oxtrackcode" value=""><br>
            *Track Company:<input type="text" name="shippingcompany" value="[{$oView->getDefaultShippingCompany()}]"><br>
            <button id="captureButton" class="actionLink" href="#">
                Capture
                [{if $oOrder->oxorder__oxsenddate and "-" neq $oOrder->oxorder__oxsenddate}] another [{/if}]
                Shipping
            </button>

        </form>
    [{/if}]



[{else}]
    <div class="messagebox">[{$sMessage}]</div>
[{/if}]


[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]