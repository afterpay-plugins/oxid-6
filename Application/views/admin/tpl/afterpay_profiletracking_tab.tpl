[{include file="headitem.tpl" title="SHOP_MODULE_GROUP_arvatoAfterpayGeneral"|oxmultilangassign}]

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

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink() }]" method="post"
	  style="padding: 0px;margin: 0px;height:0px;">
	[{block name='AFTERPAY_CONFIG_FORM'}]
	[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="AfterpayProfileTrackingTab">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{$oxid}]">

	<table cellspacing="5" cellpadding="5">
		<colgroup>
			<col width="200">
			<col>
		</colgroup>
		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayProfileTrackingEnabled" }]: </label>
			</td>
			<td class="edittext">
				<select name="confselects[arvatoAfterpayProfileTrackingEnabled]"
						id="arvatoAfterpayProfileTrackingEnabled" [{$readonly}]>
					<option value="inactive"
							[{if $confselects.arvatoAfterpayProfileTrackingEnabled == "inactive"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayProfileTrackingEnabled_inactive"}]
					</option>
					<option value="mandatory"
							[{if $confselects.arvatoAfterpayProfileTrackingEnabled == "mandatory"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayProfileTrackingEnabled_mandatory"}]
					</option>
					<option value="optional"
							[{if $confselects.arvatoAfterpayProfileTrackingEnabled == "optional"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayProfileTrackingEnabled_optional"}]
					</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayProfileTrackingUrl" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="40" name="confstrs[arvatoAfterpayProfileTrackingUrl]"
				   value="[{$confstrs.arvatoAfterpayProfileTrackingUrl}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayProfileTrackingId" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="40" name="confstrs[SHOP_MODULE_arvatoAfterpayProfileTrackingId]"
				   value="[{$confstrs.SHOP_MODULE_arvatoAfterpayProfileTrackingId}]" [{$readonly}]>
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
	[{/block}]
</form>
[{include file="bottomitem.tpl"}]