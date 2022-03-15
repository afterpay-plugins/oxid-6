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
<input type="hidden" name="cl" value="AfterpayConfigTab">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{$oxid}]">

	<table cellspacing="5" cellpadding="5">
		<colgroup>
			<col width="200">
			<col>
		</colgroup>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiDefaultShippingCompany" }]:
			</td>
			<td class="edittext">
				<input type="text" class="editinput" size="40" name="confstrs[arvatoAfterpayApiDefaultShippingCompany]"
					   value="[{$confstrs.arvatoAfterpayApiDefaultShippingCompany}]" [{$readonly}]>
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayApiDefaultRefundDescription" }]:
			</td>
			<td class="edittext">
				<input type="text" class="editinput" size="40"
					   name="confstrs[arvatoAfterpayApiDefaultRefundDescription]"
					   value="[{$confstrs.arvatoAfterpayApiDefaultRefundDescription}]" [{$readonly}]>
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayRiskChannelType" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="40" name="confstrs[arvatoAfterpayRiskChannelType]"
				   value="[{$confstrs.arvatoAfterpayRiskChannelType}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>
		<tr>
			<td class="edittext">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayRiskDeliveryType" }]:
			</td>
			<td class="edittext">
				[{oxhasrights object=$edit readonly=$readonly}]
			<input type="text" class="editinput" size="40" name="confstrs[arvatoAfterpayRiskDeliveryType]"
				   value="[{$confstrs.arvatoAfterpayRiskDeliveryType}]" [{$readonly}]>
				[{/oxhasrights}]
			</td>
		</tr>

		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayManufacturerInDescription" }]: </label>
			</td>
			<td class="edittext">
				<select name="confselects[arvatoAfterpayManufacturerInDescription]" id="arvatoAfterpayManufacturerInDescription" [{$readonly}]>
					<option value="no"
							[{if $confselects.arvatoAfterpayManufacturerInDescription == "no"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayManufacturerInDescription_no"}]
					</option>
					<option value="manufacturer"
							[{if $confselects.arvatoAfterpayManufacturerInDescription == "manufacturer"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayManufacturerInDescription_manufacturer"}]
					</option>
					<option value="vendor"
							[{if $confselects.arvatoAfterpayManufacturerInDescription == "vendor"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayManufacturerInDescription_vendor"}]
					</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayVariantInDescription" }]: </label>
			</td>
			<td class="edittext">
				<select name="confselects[arvatoAfterpayVariantInDescription]" id="arvatoAfterpayVariantInDescription" [{$readonly}]>
					<option value="yes"
							[{if $confselects.arvatoAfterpayVariantInDescription == "yes"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayVariantInDescription_yes"}]
					</option>
					<option value="no"
							[{if $confselects.arvatoAfterpayVariantInDescription == "no"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayVariantInDescription_no"}]
					</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class="edittext">
				<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayLogo" }]: </label>
			</td>
			<td class="edittext">
				<select name="confselects[arvatoAfterpayLogo]" id="arvatoAfterpayLogo" [{$readonly}]>
					<option value="Checkout"
							[{if $confselects.arvatoAfterpayLogo == "Checkout"}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayLogo_Checkout"}]
					</option>
					<option value=""
							[{if $confselects.arvatoAfterpayLogo == ""}] selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayLogo_"}]
					</option>
					<option value="Black"
							[{if $confselects.arvatoAfterpayLogo == "Black"}]
							selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayLogo_Black"}]
					</option>
					<option value="White""
							[{if $confselects.arvatoAfterpayLogo == "White"}]
							selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayLogo_White"}]
					</option>
					<option value="Grey""
							[{if $confselects.arvatoAfterpayLogo == "Grey"}]
							selected [{/if}]>
						[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayLogo_Grey"}]
					</option>
				</select>
				[{oxinputhelp ident='HELP_SHOP_MODULE_arvatoAfterpayLogo'}]
			</td>
		</tr>
		<tr>
			<td class="edittext" style="vertical-align: text-top">
				[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayExcludedArticleNr" }]:
			</td>
			<td class="edittext">
				<textarea style="text-align: left; vertical-align: text-top;height: 300px" type="text" class="edittext" name="confstrs[arvatoAfterpayExcludedArticleNr]" [{$readonly}]>[{$confstrs.arvatoAfterpayExcludedArticleNr}]</textarea>
				[{oxinputhelp ident='HELP_SHOP_MODULE_arvatoAfterpayExcludedArticleNr'}]
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