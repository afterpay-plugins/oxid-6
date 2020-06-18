[{$smarty.block.parent}]

[{assign var="oConf" value=$oViewConf->getConfig()}]
[{assign var="oSession" value=$oViewConf->getSession()}]
[{assign var="aap_pt" value=$oConf->getConfigParam('arvatoAfterpayProfileTrackingEnabled')}]
[{assign var="aap_url" value=$oConf->getConfigParam('arvatoAfterpayProfileTrackingUrl')}]
[{assign var="aap_cid" value=$oConf->getConfigParam('arvatoAfterpayProfileTrackingId')}]
[{assign var="session_id" value=$oSession->getId()}]

[{if $aap_pt and $aap_url and $aap_cid and $session_id}]
    <script type="text/javascript">
        var _itt = {
            c: '[{$aap_cid}]',
            s: 'md5[{$session_id|md5}]',
            t: 'CO'
        };
        (function() {
            var a = document.createElement('script');
            a.type = 'text/javascript'; a.async = true;
            a.src = '//[{$aap_url}]/[{$aap_cid}].js';
            var b = document.getElementsByTagName('script')[0];
            b.parentNode.insertBefore(a, b);
        })();
    </script>
[{/if}]
