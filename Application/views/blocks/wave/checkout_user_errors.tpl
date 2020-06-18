[{if $smarty.request.cfm}]
    <div class="alert alert-danger">[{$oView->getCustomerFacingMessage()}]</div>
[{elseif $smarty.request.wecorrectedyouraddress}]
    <div class="alert alert-danger">[{oxmultilang ident="MESSAGE_USER_CHECK_CHANGED_ADDRESS"}]</div>
[{/if}]
[{$smarty.block.parent}]
