[{include file="headitem.tpl" title="SHOP_MODULE_GROUP_arvatoAfterpayApi"|oxmultilangassign}]

[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
	[{else}]
	[{assign var="readonly" value=""}]
	[{/if}]

<br/>
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
		<colgroup>
			<col width="200">
			<col>
		</colgroup>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiMode" }]: </label>
			</td>
			<td class="edittext">
				<select name="confselects[arvatoAfterpayApiMode]" id="arvatoAfterpayApiMode" [{$readonly}]>
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
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiUrl" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiUrl]"
				   value="[{$confstrs.arvatoAfterpayApiUrl}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyDE" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyDE]"
				   value="[{$confstrs.arvatoAfterpayApiKeyDE}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyDEInstallment" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyDEInstallment]"
				   value="[{$confstrs.arvatoAfterpayApiKeyDEInstallment}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyAT" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyAT]"
				   value="[{$confstrs.arvatoAfterpayApiKeyAT}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyATInstallment" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyATInstallment]"
				   value="[{$confstrs.arvatoAfterpayApiKeyATInstallment}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyCH" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyCH]"
				   value="[{$confstrs.arvatoAfterpayApiKeyCH}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyNL" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyNL]"
				   value="[{$confstrs.arvatoAfterpayApiKeyNL}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiKeyBE" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiKeyBE]"
				   value="[{$confstrs.arvatoAfterpayApiKeyBE}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxUrl" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxUrl]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxUrl}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyDE" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyDE]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyDE}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyDEInstallment" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyDEInstallment]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyDEInstallment}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyAT" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyAT]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyAT}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyATInstallment" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyATInstallment]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyATInstallment}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyCH" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyCH]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyCH}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyNL" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyNL]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyNL}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiSandboxKeyBE" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="50" name="confstrs[arvatoAfterpayApiSandboxKeyBE]"
				   value="[{$confstrs.arvatoAfterpayApiSandboxKeyBE}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiRequestLogging" }]
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayApiRequestLogging]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayApiRequestLogging]" value="1" [{if $confbools.arvatoAfterpayApiRequestLogging == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>

		[{********** SAVE & LANGUAGE ********}]
		<colgroup>
			<col width="200">
			<col>
		</colgroup>
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