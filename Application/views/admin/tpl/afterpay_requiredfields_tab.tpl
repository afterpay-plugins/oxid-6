[{include file="headitem.tpl" title="SHOP_MODULE_GROUP_arvatoAfterpayRequiredFields"|oxmultilangassign}]
<br/>
[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
	[{else}]
	[{assign var="readonly" value=""}]
	[{/if}]

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
<form name="myedit" id="myedit" enctype="multipart/form-data" action="[{$oViewConf->getSelfLink()}]"
	  method="post">
	[{$oViewConf->getHiddenSid() }]
	<input type="hidden" name="cl" value="AfterpayRequiredfieldsTab">
	<input type="hidden" name="fnc" value="">
	<input type="hidden" name="language" value="[{$actlang }]">

	[{foreach from=$oView->getCountries() item="country_group" key="var_group"}]
	<div class="groupExp">
		<div>
			<a href="#" onclick="_groupExp(this);return false;"
			   class="rc"><b>[{oxmultilang ident="AFTERPAY_COUNTRY_REQUIREDFIELDS_`$country_group`"}]</b></a>
			<dl>
				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresSalutation"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInvoiceRequiresSalutation__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresSalutation"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayDebitRequiresSalutation__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresSalutation"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInstallmentsRequiresSalutation__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresBirthdate"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInvoiceRequiresBirthdate__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresBirthdate"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayDebitRequiresBirthdate__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresBirthdate"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInstallmentsRequiresBirthdate__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresSSN"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInvoiceRequiresSSN__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresSSN"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayDebitRequiresSSN__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresSSN"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInstallmentsRequiresSSN__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresFon"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInvoiceRequiresFon__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresFon"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayDebitRequiresFon__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresFon"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInstallmentsRequiresFon__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>
				<div class="spacer"></div>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInvoiceRequiresStreetNumber"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInvoiceRequiresStreetNumber__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayDebitRequiresStreetNumber"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayDebitRequiresStreetNumber__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<dt class="edittext">
					<label>[{oxmultilang ident="SHOP_MODULE_arvatoAfterpayInstallmentsRequiresStreetNumber"}]: </label>
				</dt>
				<dd class="edittext">
					[{oxhasrights object=$edit readonly=$readonly}]
					[{assign var="config_varname" value="arvatoAfterpayInstallmentsRequiresStreetNumber__$country_group"}]
				<input type="hidden" name="confbools[[{$config_varname}]] value=" 0"/>
				<input type="checkbox" class="editinput" name=confbools[[{$config_varname}]]
					   value="1"
					   [{if ($confbools.$config_varname) == 1}]checked[{/if}][{$readonly}]>
					[{/oxhasrights}]
				</dd>

				<div class="spacer"></div>

			</dl>
		</div>
	</div>
	[{/foreach}]
	<input type="submit" class="confinput" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]"
		   onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
</form>
[{include file="bottomitem.tpl"}]