<style>
    table {
        width: 99%;
        border-collapse: collapse;
    }
    td, tr {
        padding: 10px 5px;
        border: 1px solid #ccc
    }
</style>


<form name="myedit"
      action="[{$oViewConf->getSelfLink()}]"
      method="get"
        [{if $form eq "void"}]
            onsubmit="return confirm('[{oxmultilang ident="ORDER_AFTERPAY_PROMPT_REALLYVOID"}]')"
        [{elseif $form eq "refund"}]
            onsubmit="return confirm('[{oxmultilang ident="ORDER_AFTERPAY_PROMPT_REALLYREFUND"}]')"
        [{/if}]
>
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="orderafterpay">
    <input type="hidden" name="fnc" value="orderitemaction">
    <input type="hidden" name="oderitemaction" value="[{$form}]">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    [{if $form eq "refund"}]
        <input type="hidden" name="captureNo" value="[{$captureMeta->captureNumber}]">
    [{/if}]


    <table style="">
        <tr>

            [{if $form eq "capture"}]
                <th>[{oxmultilang ident="GENERAL_SUM"}] Capture</th>
            [{elseif $form eq "void"}]
                <th>[{oxmultilang ident="GENERAL_SUM"}] Void</th>
            [{elseif $form eq "refund"}]
                <th>[{oxmultilang ident="GENERAL_SUM"}] Refund</th>
            [{else}]
                [{assign var=bShowCounters value=true}]
            [{/if}]

            [{if $bShowCounters}]
                <th>[{oxmultilang ident="ORDER_AFTERPAY_ORDERED_AMOUNT"}]</th>
                <th>[{oxmultilang ident="ORDER_AFTERPAY_CAPTURED_AMOUNT"}]</th>
                <th>[{oxmultilang ident="ORDER_AFTERPAY_REFUNDED_AMOUNT"}]</th>
                <!--<th>[{oxmultilang ident="ORDER_AFTERPAY_VOIDED_AMOUNT"}]</th>-->
            [{/if}]

            <th>[{oxmultilang ident="GENERAL_ARTICLE_OXARTNUM"}]</th>
            <th>[{oxmultilang ident="GENERAL_TITLE"}]</th>

            [{if $bShowCounters}]
                <th>Typ</th>
                <th>Parameter</th>
                <th>[{oxmultilang ident="GENERAL_ARTICLE_OXSHORTDESC"}]</th>
                <th>[{oxmultilang ident="ORDER_ARTICLE_MWST"}]</th>
            [{/if}]

            <th>[{oxmultilang ident="GENERAL_ARTICLE_OXBPRICE"}]</th>
            <th>[{oxmultilang ident="GENERAL_SUMTOTAL"}]</th>
        </tr>

        [{foreach from=$items item=oItem key=productid}]
            <tr>

                [{if $form eq "capture"}]
                    <td><input name="orderitemquantity[[{$productid}]]" type="number" min="0" max="[{$oItem->quantity}]"
                               step="1" value="[{$oItem->quantity}]"> / [{$oItem->quantity}] max.
                    </td>
                [{elseif $form eq "void"}]
                    <td><input name="orderitemquantity[[{$productid}]]" type="number" min="0" max="[{$oItem->quantity}]"
                               step="1" value="[{$oItem->quantity}]"> / [{$oItem->quantity}] max.
                    </td>
                [{elseif $form eq "refund"}]
                    <td><input name="orderitemquantity[[{$productid}]]" type="number" min="0" max="[{$oItem->leftToCaptureQuantity}]"
                               step="1" value="[{$oItem->leftToCaptureQuantity}]"> / [{$oItem->leftToCaptureQuantity}] max.
                    </td>
                [{/if}]

                [{if $bShowCounters}]
                    <td>[{$oItem->orderedQty}]</td>
                    <td>[{$oItem->capturedQty}]</td>
                    <td>[{$oItem->refundedQty}]</td>
                   <!-- <td>[{$oItem->voidedQty}]</td> -->
                [{/if}]

                <td>[{$oItem->oxArticle->oxarticles__oxartnum}]</td>
                <td>[{$oItem->description}]</td>
                [{if $bShowCounters}]
                    <td>-</td>
                    <td>-</td>
                    <td>[{$oItem->oxArticle->oxarticles__oxshortdesc}]</td>
                    <td>[{$oItem->vatPercent}]</td>
                [{/if}]
                <td>[{$oItem->grossUnitPrice|string_format:"%.2f"}]</td>
                <td>[{math equation="x*y" x=$oItem->grossUnitPrice y=$oItem->quantity format="%.2f"}]</td>
            </tr>
        [{/foreach}]

        [{if $form eq "capture"}]
            <tr>
                <td colspan="9">
                    <button id="captureButton" class="actionLink" href="#">Capture</button>
                </td>
            </tr>
        [{elseif $form eq "void"}]
            <tr>
                <td colspan="9">
                    <button id="voidButton" class="actionLink" href="#">Void</button>
                </td>
            </tr>
        [{elseif $form eq "refund"}]
            <tr>
                <td colspan="9">
                    <button id="refundButton" class="actionLink" href="#">Refund</button>
                    [{oxmultilang ident="ORDER_AFTERPAY_FORCAPTURENO"}]  [{$captureMeta->captureNumber}] ([{$captureMeta->insertedAt}])
                </td>
            </tr>
        [{/if}]

    </table>

</form>

