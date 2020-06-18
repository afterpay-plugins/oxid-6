[{assign var="iPayError" value=$oView->getPaymentError()}]
[{if $iPayError == -1337}]
    <div class="alert alert-danger">[{oxmultilang ident="MESSAGE_PAYMENT_BANK_ACCOUNT_INVALID"}]</div>
[{elseif $iPayError == -13337}]
    <div class="alert alert-danger">[{oxmultilang ident="MESSAGE_PAYMENT_SELECT_INSTALLMENT_PLAN"}]</div>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
