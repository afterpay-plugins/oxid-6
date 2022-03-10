[{include file="headitem.tpl" title="SHOP_MODULE_GROUP_arvatoAfterpayRequiredFields"|oxmultilangassign}]
<br/>
[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
	[{else}]
	[{assign var="readonly" value=""}]
	[{/if}]
<form name="myedit" id="myedit" enctype="multipart/form-data" action="[{$oViewConf->getSelfLink() }]"
	  method="post">
	[{$oViewConf->getHiddenSid() }]
	<input type="hidden" name="cl" value="AfterpayRequiredfieldsTab">
	<input type="hidden" name="fnc" value="">
	<input type="hidden" name="language" value="[{$actlang }]">

	<table cellspacing="5" cellpadding="5">
		<colgroup>
			<col width="200">
			<col>
		</colgroup>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresBirthdate" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayInvoiceRequiresBirthdate]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayInvoiceRequiresBirthdate]" value="1" [{if $confbools.arvatoAfterpayInvoiceRequiresBirthdate == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresBirthdate" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayDebitRequiresBirthdate]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayDebitRequiresBirthdate]" value="1" [{if $confbools.arvatoAfterpayDebitRequiresBirthdate == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresBirthdate" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayInstallmentsRequiresBirthdate]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayInstallmentsRequiresBirthdate]" value="1" [{if $confbools.arvatoAfterpayInstallmentsRequiresBirthdate == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresSSN" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayInvoiceRequiresSSN]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayInvoiceRequiresSSN]" value="1" [{if $confbools.arvatoAfterpayInvoiceRequiresSSN == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>

		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresSSN" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayDebitRequiresSSN]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayDebitRequiresSSN]" value="1" [{if $confbools.arvatoAfterpayDebitRequiresSSN == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>

		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresSSN" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayInstallmentsRequiresSSN]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayInstallmentsRequiresSSN]" value="1" [{if $confbools.arvatoAfterpayInstallmentsRequiresSSN == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>

		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresFon" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayInvoiceRequiresFon]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayInvoiceRequiresFon]" value="1" [{if $confbools.arvatoAfterpayInvoiceRequiresFon == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresFon" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayDebitRequiresFon]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayDebitRequiresFon]" value="1" [{if $confbools.arvatoAfterpayDebitRequiresFon == 1}]checked[{/if}][{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresFon" }]: </label>
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="hidden" name="confbools[arvatoAfterpayInstallmentsRequiresFon]" value="0"/>
			<input type="checkbox" class="editinput" name="confbools[arvatoAfterpayInstallmentsRequiresFon]" value="1" [{if $confbools.arvatoAfterpayInstallmentsRequiresFon == 1}]checked[{/if}][{$readonly}]>
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