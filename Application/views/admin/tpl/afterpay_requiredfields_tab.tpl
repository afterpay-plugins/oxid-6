[{include file="headitem.tpl" title="SHOP_MODULE_GROUP_arvatoAfterpayRequiredFields"|oxmultilangassign}]
<br/>
[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
[{else}]
	[{assign var="readonly" value=""}]
[{/if}]
<style>
    td, th {
        padding: 3px 8px;
        border-right: 1px solid #0000004d;
        text-align: center;
    }
    td:first-child {
        text-align: left;
    }
    td:last-child, th:last-child {
        border-right: none;
    }
</style>
<script type="text/javascript">
    function _groupExp(el) {
        var _cur = el.parentNode;

        if (document.querySelector('.yui-panel-container')) {
            document.querySelector('.yui-panel-container').classList.add('yui-overlay-hidden');
            document.querySelector('.yui-panel-container').style.visibility = "hidden";
        }

        if (_cur.className == "exp") {
            _cur.className = "";
        } else {
            _cur.className = "exp";
        }
    }
</script>
<form name="myedit" id="myedit" enctype="multipart/form-data" action="[{$oViewConf->getSelfLink()}]" method="post">
	[{block name='AFTERPAY_PROFILETRACKING_FORM'}]
	[{$oViewConf->getHiddenSid() }]
	<input type="hidden" name="cl" value="AfterpayRequiredfieldsTab">
	<input type="hidden" name="fnc" value="">
	<input type="hidden" name="language" value="[{$actlang }]">

	[{oxmultilang ident='SHOP_MODULE_arvatoAfterpayRequireFieldsExplanation'}]
	<br><br>

	[{foreach from=$oView->getCountries() key="country_id" item="country_name"}]
	<div class="groupExp">
		<div>
			<a href="#" onclick="_groupExp(this);return false;"
			   class="rc"><b>[{oxmultilang ident="AFTERPAY_COUNTRY_REQUIREDFIELDS_`$country_name`"}]</b></a>
			<dl>
				<table cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr>
							<th>[{oxmultilang ident='SHOP_MODULE_arvatoAfterpayRequireFields'}]</th>
							<th>[{oxmultilang ident='SHOP_MODULE_arvatoAfterpayInvoice'}]</th>
							<th>[{oxmultilang ident='SHOP_MODULE_arvatoAfterpayDebit'}]</th>
							<th>[{oxmultilang ident='SHOP_MODULE_arvatoAfterpayInstallments'}]</th>
						</tr>
					</thead>
					[{foreach from=$oView->getFields() item='FieldNames' name='moduleList'}]
						<tr>
							<td>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayRequires$FieldNames"}]</td>

							<td>
								[{oxhasrights object=$edit readonly=$readonly}]
								[{assign var="config_varname" value="arvatoAfterpayInvoiceRequires$FieldNames$country_id"}]
								<input type="hidden" name="confbools[[{$config_varname}]]" value="0"/>
								<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
									   value="1" [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
								[{/oxhasrights}]
							</td>

							<td>
								[{oxhasrights object=$edit readonly=$readonly}]
								[{assign var="config_varname" value="arvatoAfterpayDebitRequires$FieldNames$country_id"}]
								<input type="hidden" name="confbools[[{$config_varname}]]" value="0"/>
								<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
								   value="1" [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
								[{/oxhasrights}]
							</td>

							<td>
								[{oxhasrights object=$edit readonly=$readonly}]
								[{assign var="config_varname" value="arvatoAfterpayInstallmentsRequires$FieldNames$country_id"}]
							<input type="hidden" name="confbools[[{$config_varname}]]" value="0"/>
							<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
								   value="1" [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
								[{/oxhasrights}]
							</td>
						</tr>
					[{/foreach}]
				</table>
				<table>
					<td class="edittext">
						<label for="[{$config_varname}]">[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayHorizonID" }]</label>
					</td>
					<td class="edittext">
						[{oxhasrights object=$edit readonly=$readonly}]
						[{assign var="config_varname" value="arvatoAfterpayHorizonID$country_id"}]
					<input type="text" class="editinput" size="40" name=confstrs[[{$config_varname}]]
						   value="[{$confstrs.$config_varname}]" [{$readonly}] id="[{$config_varname}]">
						[{/oxhasrights}]
						[{oxinputhelp ident='HELP_SHOP_MODULE_arvatoAfterpayHorizonID'}]
					</td>
				</table>
			</dl>
		</div>
	</div>
	[{/foreach}]
	<input type="submit" class="confinput" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]"
		   onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
	[{/block}]
</form>
[{include file="bottomitem.tpl"}]