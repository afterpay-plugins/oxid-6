[{$smarty.block.parent}]
[{oxhasrights object=$edit field='aapproductgroup' readonly=$readonly }]
    <tr>
        <td class="edittext">
            AfterPay ProductGroup ([{$edit->getAfterpayProductGroup()}])
        </td>
        <td class="edittext">
            <input type="text" class="editinput" size="10"
                   maxlength="[{$edit->oxarticles__aapproductgroup->fldmax_length}]"
                   name="editval[oxarticles__aapproductgroup]" value="[{$edit->oxarticles__aapproductgroup->value}]"
                    [{include file="help.tpl" helpid=article_vat}]>
            [{ oxinputhelp ident="HELP_ARTICLE_MAIN_AAPPRODUCTGROUP" }]
        </td>
    </tr>
[{/oxhasrights}]