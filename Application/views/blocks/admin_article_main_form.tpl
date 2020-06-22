[{$smarty.block.parent}]

<tr>
    <td class="edittext">
            [{oxmultilang ident="ORDER_AFTERPAY_MAIN_CATEGORY"}]
    </td>
    <td class="edittext"> [{$edit->getMainCategory()}]
            [{oxinputhelp ident="ORDER_AFTERPAY_CATEGORY_MAIN_HELP"}]
    </td>
</tr>