{if $iLevel > 12}{$iLevel=12}{/if}
<ul class="dev-config-list">
    {foreach $aConfig as $sKey => $xVal}
        <li class="dev-config-item dev-config-level-{$iLevel}">
            <div class="dev-config-key">
                <span class="dev-config-parent">{$sParentKey}.</span><strong>{$sKey}</strong>
            </div>
            {if is_array($xVal) AND !isset($xVal.0)}
                <ul class="dev-config-list">
                    {include file="./action.dev.config.item.tpl" aConfig=$xVal sParentKey="$sParentKey.$sKey" iLevel=$iLevel+1}
                </ul>
            {else}
                {include file="./action.dev.config.item-value.tpl" xValue=$xVal sParentKey="$sParentKey.$sKey" iLevel=$iLevel+1}
            {/if}
        </li>
    {/foreach}
</ul>

