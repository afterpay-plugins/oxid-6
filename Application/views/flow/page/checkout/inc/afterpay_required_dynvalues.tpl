[{if $aAfterpayRequiredFields.$sPayment.Salutation}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_sal">
            * [{oxmultilang ident="TITLE"}]
        </label>
        <div class="col-lg-9">
            [{include file="form/fieldset/salutation.tpl" id="afterpay`$sPayment`_sal" name="dynvalue[apsal][`$sPayment`]" class="form-control selectpicker" value="" }]
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.SSN}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_ssn">
            * [{oxmultilang ident="ORDER_OVERVIEW_APSSN"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_ssn" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apssn][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.FName}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_fname">
            * [{oxmultilang ident="FIRST_NAME"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_fname" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apfname][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.LName}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_lname">
            * [{oxmultilang ident="LAST_NAME"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_lname" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[aplname][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.Birthdate}]
    <div class="form-group">
        <label class="control-label col-lg-3 " for="afterpay[{$sPayment}]_bd">
            * [{oxmultilang ident="ORDER_OVERVIEW_APBIRTHDAY"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_bd" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apbirthday][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.Fon}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_f">
            * [{oxmultilang ident="ORDER_OVERVIEW_APPHONE"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_f" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apfon][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.Street or $aAfterpayRequiredFields.$sPayment.StreetNumber}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_street">
            * [{oxmultilang ident="STREET_AND_STREETNO"}]
        </label>
        <div class="col-xs-8 col-lg-6">
            <input id="afterpay[{$sPayment}]_street" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apstreet][[{$sPayment}]]" value="">
        </div>
        <div class="col-xs-4 col-lg-3">
            <input id="afterpay[{$sPayment}]_streetnr" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apstreetnr][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.Zip or $aAfterpayRequiredFields.$sPayment.City}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_city">
            * [{oxmultilang ident="POSTAL_CODE_AND_CITY"}]
        </label>
        <div class="col-xs-5 col-lg-3">
            <input id="afterpay[{$sPayment}]_zip" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apzip][[{$sPayment}]]" value="">
        </div>
        <div class="col-xs-7 col-lg-6">
            <input id="afterpay[{$sPayment}]_city" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apcity][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]