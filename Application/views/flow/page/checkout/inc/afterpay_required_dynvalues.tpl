[{if $aAfterpayRequiredFields.$sPayment.Salutation}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_sal">
            * [{oxmultilang ident="SHOP_MODULE_arvatoAfterpayRequiresSalutation"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_sal" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apsal][[{$sPayment}]]" value="">
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
            * [{oxmultilang ident="ORDER_OVERVIEW_FIRSTNAME"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_fname" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apfname][[{$sPayment}]]" value="">
        </div>
    </div>
    [{/if}]

[{if $aAfterpayRequiredFields.$sPayment.LName}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_lname">
            * [{oxmultilang ident="ORDER_OVERVIEW_LASTNAME"}]
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

[{if $aAfterpayRequiredFields.$sPayment.Street}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_street">
            * [{oxmultilang ident="ORDER_OVERVIEW_STREET"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_street" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apstreet][[{$sPayment}]]" value="">
        </div>
    </div>
[{/if}]

[{if $aAfterpayRequiredFields.$sPayment.StreetNumber}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_streetnr">
            * [{oxmultilang ident="SHOP_MODULE_arvatoAfterpayRequiresStreetNumber"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_streetnr" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apstreetnr][[{$sPayment}]]" value="">
        </div>
    </div>
[{/if}]

[{if $aAfterpayRequiredFields.$sPayment.Zip}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_ssn">
            * [{oxmultilang ident="ORDER_OVERVIEW_ZIP"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_zip" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apzip][[{$sPayment}]]" value="">
        </div>
    </div>
[{/if}]

[{if $aAfterpayRequiredFields.$sPayment.City}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$sPayment}]_city">
            * [{oxmultilang ident="ORDER_OVERVIEW_CITY"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$sPayment}]_city" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apcity][[{$sPayment}]]" value="">
        </div>
    </div>
[{/if}]