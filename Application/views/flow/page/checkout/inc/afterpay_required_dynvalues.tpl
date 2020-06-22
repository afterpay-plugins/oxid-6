[{if $aAfterpayRequiredFields.$payment.Birthdate}]
    <div class="form-group">
        <label class="control-label col-lg-3 " for="afterpay[{$payment}]_bd">
            * [{oxmultilang ident="ORDER_OVERVIEW_APBIRTHDAY"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$payment}]_bd" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apbirthday][[{$payment}]]" value="">
        </div>
    </div>
[{/if}]

[{if $aAfterpayRequiredFields.$payment.Fon}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$payment}]_f">
            * [{oxmultilang ident="ORDER_OVERVIEW_APPHONE"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$payment}]_f" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apfon][[{$payment}]]" value="">
        </div>
    </div>
[{/if}]

[{if $aAfterpayRequiredFields.$payment.SSN}]
    <div class="form-group">
        <label class="control-label col-lg-3" for="afterpay[{$payment}]_ssn">
            * [{oxmultilang ident="ORDER_OVERVIEW_APSSN"}]
        </label>
        <div class="col-lg-9">
            <input id="afterpay[{$payment}]_ssn" type="text" class="form-control textbox" size="20" maxlength="64" name="dynvalue[apssn][[{$payment}]]" value="">
        </div>
    </div>
[{/if}]