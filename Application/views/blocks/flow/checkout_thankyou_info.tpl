<div class="form-group row afterpay-thankyou">
	<label class="control-label col-xs-12 col-md-6 col-lg-8">
		<strong>[{oxmultilang ident="AFTERPAY_THANKYOU_SHOPFIRST"}].</strong><br><br>
		[{oxmultilang ident="AFTERPAY_THANKYOU_TEXT" args=$oxcmp_shop->oxshops__oxname->value}]<br><br><br>
		[{if !$oView->getMailError()}]
		[{oxmultilang ident="MESSAGE_YOU_RECEIVED_ORDER_CONFIRM"}]<br>
		[{else}]<br>
		[{oxmultilang ident="MESSAGE_CONFIRMATION_NOT_SUCCEED"}]<br>
		[{/if}]
		<br>
		[{oxmultilang ident="MESSAGE_WE_WILL_INFORM_YOU"}]<br><br>
	</label>
	<div class="col-xs-12 col-md-6 col-lg-4 afterpay-imgwrapper">
		<img src="[{$oViewConf->getAfterpayLogoUrl()}]" class="frontendAfterpayLogoAsTitle">
	</div>
</div>
