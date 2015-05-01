{extends file="_index.tpl"}

{block name="layout_vars"}
    {$menu="dev"}
    {$noSidebar=true}
{/block}

{block name="layout_content"}
    <h1>Current configuration (LEVEL_CUSTOM)</h1>

    <ul class="dev-config-root">
        {foreach $aConfig as $sKey => $xVal}
            {if is_array($xVal)}
                <li>
                    <strong>{$sKey}</strong>
                    {include file="./action.dev.config.item.tpl" aConfig=$xVal sParentKey=$sKey iLevel=1}
                </li>
            {/if}
        {/foreach}
    </ul>

    <script>
        $('.js-config-explode').click(function(){
            var key=$(this).data('config-key');

            $('#config-key-' + key).toggle();
            return false;
        });
    </script>
{/block}