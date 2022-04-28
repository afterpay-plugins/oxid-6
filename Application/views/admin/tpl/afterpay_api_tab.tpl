[{include file="headitem.tpl" title="SHOP_MODULE_GROUP_arvatoAfterpayApi"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="myedit" id="myedit" enctype="multipart/form-data" action="[{$oViewConf->getSelfLink() }]"
      method="post">
    [{$oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="AfterpayApiTab">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{$actlang }]">

    <table cellspacing="5" cellpadding="5">

        <tr>
            <td colspan="2">
                <fieldset>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="250">
                            <col>
                        </colgroup>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiMode">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode" }]</label>
                            </td>
                            <td class="edittext">
                                <select name="confselects[arvatoAfterpayApiMode]"
                                        id="arvatoAfterpayApiMode" [{$readonly}]>
                                    <option value="live"
                                            [{if $confselects.arvatoAfterpayApiMode == "live"}] selected [{/if}]>
                                        [{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode_live"}]
                                    </option>
                                    <option value="partner"
                                            [{if $confselects.arvatoAfterpayApiMode == "partner"}] selected [{/if}]>
                                        [{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode_partner"}]
                                    </option>
                                    <option value="sandbox"
                                            [{if $confselects.arvatoAfterpayApiMode == "sandbox"}] selected [{/if}]>
                                        [{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode_sandbox"}]
                                    </option>
                                </select>
                                [{oxinputhelp ident="HELP_SHOP_MODULE_arvatoAfterpayApiMode"}]
                            </td>
                        </tr>

                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiRequestLogging">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiRequestLogging" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="hidden" name="confbools[arvatoAfterpayApiRequestLogging]" value="0"/>
                                    <input type="checkbox" class="editinput"
                                           name="confbools[arvatoAfterpayApiRequestLogging]"
                                           id="arvatoAfterpayApiRequestLogging" value="1"
                                           [{if $confbools.arvatoAfterpayApiRequestLogging == 1}]checked[{/if}][{$readonly}]>
                                [{/oxhasrights}]
                                [{oxinputhelp ident="HELP_SHOP_MODULE_arvatoAfterpayApiRequestLogging"}]
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <fieldset>
                    <legend>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode_live"}]</legend>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="250">
                            <col>
                        </colgroup>

                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiUrl">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiUrl" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiUrl]"
                                           value="[{$confstrs.arvatoAfterpayApiUrl}]" [{$readonly}]
                                           id="arvatoAfterpayApiUrl">
                                [{/oxhasrights}]
                                [{oxinputhelp ident="HELP_SHOP_MODULE_arvatoAfterpayApiUrl"}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyDE">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyDE" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyDE]" id="arvatoAfterpayApiKeyDE"
                                           value="[{$confstrs.arvatoAfterpayApiKeyDE}]" [{$readonly}]>
                                [{/oxhasrights}]
                                [{oxinputhelp ident="HELP_SHOP_MODULE_arvatoAfterpayApiKeyDE"}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyDEInstallment">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyDEInstallment" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyDEInstallment]"
                                           id="arvatoAfterpayApiKeyDEInstallment"
                                           value="[{$confstrs.arvatoAfterpayApiKeyDEInstallment}]" [{$readonly}]>
                                [{/oxhasrights}]
                                [{oxinputhelp ident="HELP_SHOP_MODULE_arvatoAfterpayApiKeyDEInstallment"}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyAT">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyAT" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyAT]" id="arvatoAfterpayApiKeyAT"
                                           value="[{$confstrs.arvatoAfterpayApiKeyAT}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyATInstallment">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyATInstallment" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyATInstallment]"
                                           id="arvatoAfterpayApiKeyATInstallment"
                                           value="[{$confstrs.arvatoAfterpayApiKeyATInstallment}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyCH">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyCH" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyCH]" id="arvatoAfterpayApiKeyCH"
                                           value="[{$confstrs.arvatoAfterpayApiKeyCH}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyNL">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyNL" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyNL]" id="arvatoAfterpayApiKeyNL"
                                           value="[{$confstrs.arvatoAfterpayApiKeyNL}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiKeyBE">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyBE" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiKeyBE]" id="arvatoAfterpayApiKeyBE"
                                           value="[{$confstrs.arvatoAfterpayApiKeyBE}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>


                    </table>
                </fieldset>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <fieldset>
                    <legend>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode_sandbox"}]</legend>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="250">
                            <col>
                        </colgroup>

                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxUrl">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxUrl" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxUrl]" id="arvatoAfterpayApiSandboxUrl"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxUrl}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyDE">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyDE" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyDE]"
                                           id="arvatoAfterpayApiSandboxKeyDE"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyDE}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyDEInstallment">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyDEInstallment" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyDEInstallment]"
                                           id="arvatoAfterpayApiSandboxKeyDEInstallment"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyDEInstallment}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyAT">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyAT" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyAT]"
                                           id="arvatoAfterpayApiSandboxKeyAT"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyAT}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyATInstallment">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyATInstallment" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyATInstallment]"
                                           id="arvatoAfterpayApiSandboxKeyATInstallment"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyATInstallment}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyCH">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyCH" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyCH]"
                                           id="arvatoAfterpayApiSandboxKeyCH"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyCH}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyNL">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyNL" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyNL]"
                                           id="arvatoAfterpayApiSandboxKeyNL"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyNL}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiSandboxKeyBE">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyBE" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiSandboxKeyBE]"
                                           id="arvatoAfterpayApiSandboxKeyBE"
                                           value="[{$confstrs.arvatoAfterpayApiSandboxKeyBE}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>


                    </table>
                </fieldset>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <fieldset>
                    <legend>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode_partner"}]</legend>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="250">
                            <col>
                        </colgroup>

                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerUrl">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerUrl" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerUrl]" id="arvatoAfterpayApiPartnerUrl"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerUrl}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyDE">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyDE" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyDE]"
                                           id="arvatoAfterpayApiPartnerKeyDE"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyDE}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyDEInstallment">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyDEInstallment" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyDEInstallment]"
                                           id="arvatoAfterpayApiPartnerKeyDEInstallment"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyDEInstallment}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyAT">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyAT" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyAT]"
                                           id="arvatoAfterpayApiPartnerKeyAT"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyAT}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyATInstallment">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyATInstallment" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyATInstallment]"
                                           id="arvatoAfterpayApiPartnerKeyATInstallment"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyATInstallment}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyCH">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyCH" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyCH]"
                                           id="arvatoAfterpayApiPartnerKeyCH"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyCH}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyNL">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyNL" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyNL]"
                                           id="arvatoAfterpayApiPartnerKeyNL"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyNL}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <label for="arvatoAfterpayApiPartnerKeyBE">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiPartnerKeyBE" }]</label>
                            </td>
                            <td class="edittext">
                                [{oxhasrights object=$edit readonly=$readonly}]
                                    <input type="text" class="editinput" size="50"
                                           name="confstrs[arvatoAfterpayApiPartnerKeyBE]"
                                           id="arvatoAfterpayApiPartnerKeyBE"
                                           value="[{$confstrs.arvatoAfterpayApiPartnerKeyBE}]" [{$readonly}]>
                                [{/oxhasrights}]
                            </td>
                        </tr>


                    </table>
                </fieldset>
            </td>
        </tr>

        [{********** SAVE & LANGUAGE ********}]
        <tr>
            <td colspan="3">
                [{if $error != ''}]
                    <span style="color:#f00;">[{oxmultilang ident=$error}]</span>
                [{else}]
                    &nbsp;
                [{/if}]
            </td>
        </tr>

        <tr>
            <td class="edittext">&nbsp;
            </td>
            <td valign="top" class="conftext">
                [{if !$readonly}]
                    <input type="submit" class="confinput" id="oLockButton" name="save"
                           value="[{oxmultilang ident="GENERAL_SAVE" }]"
                           onclick="document.myedit.fnc.value='save'">
                [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">&nbsp;
            </td>
            <td class="edittext">
                [{if $smarty.post.save}]
                    <div style="color: #008000">[{oxmultilang ident="AFTERPAY_SAVED" }]</div>
                [{/if}]
            </td>
        </tr>
    </table>
</form>
[{include file="bottomitem.tpl"}]