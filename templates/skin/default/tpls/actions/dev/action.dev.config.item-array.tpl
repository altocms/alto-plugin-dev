{foreach $aConfigValue as $xConfigItem}
    {if $xConfigItem@first}[{/if}
    {if is_array($xConfigItem)}
        {include file="./action.dev.config.item.tpl" aConfigValue=$xConfigItem iLevel=$iLevel+1}
    {else}
        {$xConfigItem}{if !$xConfigItem@last},{/if}
    {/if}
    {if $xConfigItem@last}]{/if}
{/foreach}