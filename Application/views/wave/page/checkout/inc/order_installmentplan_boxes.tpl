[{oxstyle include=$oViewConf->getModuleUrl('arvatoafterpay','Application/views/out/flow/src/css/afterpay.min.css') }]

[{if $finalOrderStep}]
    <form action="[{$oViewConf->getSslSelfLink()}]" method="post" id="changeInstallmentPlan">
        <div class="hidden">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="order">
            <input type="hidden" name="fnc" value="updateSelectedInstallmentPlanProfileIdInSession">
            <input type="hidden" name="afterpayInstallmentProfileId" value="[{$afterpayInstallmentProfileId}]"
                   id="afterpayInstallmentProfileId">
        </div>
    </form>
[{else}]
    <input id="afterpayInstallmentProfileId" type="hidden" name="dynvalue[afterpayInstallmentProfileId]"
           value="[{$afterpayInstallmentProfileId}]">
[{/if}]


<div class="afterpayinstallments">
    <div class="AP_InstallmentWidget">
        <div class="AP_InstallmentOptions">

            [{foreach from=$aAvailableAfterpayInstallmentPlans item=installment name=installment}]

                [{assign var="first" value=$smarty.foreach.installment.first}]
                [{assign var="last" value=$smarty.foreach.installment.last}]

                [{assign var="active" value=false}]
                [{if $afterpayInstallmentProfileId eq $installment->installmentProfileNumber}]
                    [{assign var="active" value=true}]
                [{/if}]
                <div class="AP_InstallmentOption [{if $first}]AP_InstallmentOption_first[{/if}] [{if $active}]AP_InstallmentOption_active[{/if}] [{if $first and $active}]AP_InstallmentOption_first_active[{/if}]"
                     data-ap-installment-profile="[{$installment->installmentProfileNumber}]"

                    onclick="
                    $('.AP_InstallmentOption_active').removeClass('AP_InstallmentOption_active');
                    $('.AP_InstallmentDetail_active').removeClass('AP_InstallmentDetail_active');
                    $(this).addClass('AP_InstallmentOption_active');
                    $('#AP_InstallmentDetail_[{$installment->installmentProfileNumber}]').addClass('AP_InstallmentDetail_active');
                    $('#afterpayInstallmentProfileId').val([{$installment->installmentProfileNumber}]);

                    [{if $finalOrderStep}] $('#changeInstallmentPlan').submit(); [{/if}]"
                >
                    <div class="AP_InstallmentMonthlyAmount">[{oxprice price=$installment->installmentAmount currency=$currency}]
                        / Monat
                    </div>
                    <div class="AP_InstallmentMonths">in [{$installment->numberOfInstallments}] Raten</div>
                    <div class="AP_InstallmentSelected">✓</div>
                    <div style="clear:both"></div>
                </div>
            [{/foreach}]

        </div>

        <div class="AP_InstallmentDetails">
            [{foreach from=$aAvailableAfterpayInstallmentPlans item=installment name=installment}]

                [{assign var="first" value=$smarty.foreach.installment.first}]
                [{assign var="last" value=$smarty.foreach.installment.last}]

                [{assign var="active" value=false}]
                [{if $afterpayInstallmentProfileId eq $installment->installmentProfileNumber}]
                    [{assign var="active" value=true}]
                [{/if}]

                <div id="AP_InstallmentDetail_[{$installment->installmentProfileNumber}]" class="AP_InstallmentDetail [{if $active}]AP_InstallmentDetail_active[{/if}]"
                     data-ap-installment-profile="[{$installment->installmentProfileNumber}]">
                    <ul>
                        <li>Jeden Monat dieselbe Rate, keine Überraschungen</li>
                        <li>Fester Zinssatz von <b>[{$installment->interestRate}]%</b> p.a.</li>
                        <li>Effektiver Zinssatz von <b>[{$installment->effectiveInterestRate}]%</b> p.a.</li>
                        [{if $installment->basketAmount neq $installment->totalAmount}]
                            <li>Für den Warenkorb von [{oxprice price=$installment->basketAmount currency=$currency}]
                                ergibt sich bei der Auswahl der Ratenzahlung über [{$installment->numberOfInstallments}]
                                Monate ein Gesamtkreditbetrag von
                                <b>[{oxprice price=$installment->totalAmount currency=$currency}]</b>
                            </li>
                        [{/if}]
                    </ul>
                    [{if $installment->basketAmount >= 200 and $installment->effectiveInterestRate > 0}]
                        <div class="AP_InstallmentTotalPrice">
                            Klicke <a href="http://documents-cdn.afterpay-demo.com/docs/t_c/en_de/234/invoice"
                                      class="AP_linkToModalView">hier</a> um weitere Informationen
                            , die Standardinformationen für Verbraucherkredite und beispielhafte Tilgungspläne
                            anzuzeigen.
                        </div>
                    [{/if}]
                </div>
            [{/foreach}]
        </div>

    </div>

    <div class="AP_IBANInput">
        Wir ziehen die Raten bequem und komfortabel von Deinem Konto ein. Bitte gib daher Deine IBAN ein:
    </div>
</div>


<script type="text/javascript">
    /* Please specify the hidden field in which the installment number should we written in */
    var AP_installmentProfileInputField = 'afterpayInstallmentProfileId';
</script>