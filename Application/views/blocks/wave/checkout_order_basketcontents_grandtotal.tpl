[{if $afterpayTotalInterestAmount and $afterpayNewGrandTotal}]
    <tr>
        <th>
            <strong>[{oxmultilang ident="AFTERPAY_INSTALLMENT_TOTALINSTALLMENTCOST" suffix="COLON"}]</strong>
        </th>
        <td>
            [{oxprice price=$afterpayTotalInterestAmount currency=$currency}]
        </td>
    </tr>
    <tr>
        <th class="lead">
            <strong>[{oxmultilang ident="GRAND_TOTAL" suffix="COLON"}]</strong>
        </th>
        <td id="basketGrandTotal" class="lead">
            <strong> [{oxprice price=$afterpayNewGrandTotal currency=$currency}]</strong>
        </td>
    </tr>
[{else}]
    [{$smarty.block.parent}]
[{/if}]

