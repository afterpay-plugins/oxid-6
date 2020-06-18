[{$smarty.block.parent}]
[{oxhasrights object=$edit field='aapproductgroup' readonly=$readonly }]
    <tr>
        <td class="edittext">
            Arvato AfterPay ProductGroup
        </td>
        <td class="edittext">
            <input type="text" class="editinput" size="10"
                   maxlength="[{$edit->oxcategories__aapproductgroup->fldmax_length}]"
                   name="editval[oxcategories__aapproductgroup]"
                   value="[{$edit->oxcategories__aapproductgroup->value}]">
        </td>
    </tr>
[{/oxhasrights}]
