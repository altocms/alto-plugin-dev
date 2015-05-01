<span class="dev-config-value">
{if (is_array($xValue))}
    <span class="dev-config-value_array">
    {foreach $xValue as $iIdx =>$xVal}
        {$iIdx}: {include file="./action.dev.config.item-value.tpl" xValue=$xVal sParentKey="$sParentKey.$sKey" iLevel=$iLevel+1}<br>
    {/foreach}
    </span>
{elseif (is_object($xValue))}
    <span class="dev-config-value_number">{get_class($xValue)}</span>
{elseif (is_bool($xValue))}
    <span class="dev-config-value_bool">{if $xValue}TRUE{else}FALSE{/if}</span>
{elseif (is_integer($xValue)) OR (is_float($xValue))}
    <span class="dev-config-value_number">{$xValue}</span>
{else}
    <span class="dev-config-value_string">{$xValue}</span>
{/if}
</span>
