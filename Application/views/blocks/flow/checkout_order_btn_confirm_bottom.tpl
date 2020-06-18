[{assign var='afterpayErrorMessages' value=$oView->getAfterpayErrorMessages()}]
[{if !$afterpayErrorMessages}]
    [{$smarty.block.parent}]
[{/if}]
