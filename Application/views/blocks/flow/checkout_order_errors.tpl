[{$smarty.block.parent}]
[{assign var='afterpayErrorMessages' value=$oView->getAfterpayErrorMessages()}]
[{if $afterpayErrorMessages}]
    [{foreach from=$afterpayErrorMessages item='error'}]
        <p class="alert alert-danger">[{$error}]</p>
    [{/foreach}]
[{/if}]
