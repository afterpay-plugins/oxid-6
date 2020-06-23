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