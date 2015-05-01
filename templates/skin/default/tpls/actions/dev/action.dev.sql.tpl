{extends file="_index.tpl"}

{block name="layout_vars"}
    {$menu="dev"}
    {$noSidebar=true}
{/block}

{block name="layout_content"}
    <h1>SQL statistics</h1>

    <div role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#queries" aria-controls="queries" role="tab" data-toggle="tab">Queries</a></li>
            <li role="presentation"><a href="#sources" aria-controls="sources" role="tab" data-toggle="tab">Sources</a></li>
            <li role="presentation"><a href="#chains" aria-controls="chains" role="tab" data-toggle="tab">Chains</a></li>
            <li role="presentation"><a href="#list" aria-controls="list" role="tab" data-toggle="tab">List</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="queries">
                <table class="table table-bordered table-condensed" style="width: 100%;">
                    {foreach $aQueryCnt as $sKey => $aData}
                        <tr>
                            <td>
                                cnt: {$aData.cnt}<br>
                                <div style="overflow-x: auto;">
                                    {$aData.data.src}
                                    <pre class="prettyprint lang-sql" style="word-wrap: normal;">{$aData.data.sql}</pre>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="sources">
                <table class="table table-bordered table-condensed">
                    {foreach $aSourceCnt as $sKey => $aData}
                        <tr>
                            <td>
                                cnt: {$aData.cnt}<br>
                                <div style="overflow-x: auto;">
                                    {$aData.data.src}
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="chains">
                <table class="table table-bordered table-condensed">
                    {foreach $aChainsCnt as $sKey => $aData}
                        <tr>
                            <td>
                                cnt: {$aData.cnt}, len: {sizeof($aData.list)}<br>
                                <div style="overflow-x: auto;">
                                    {foreach $aData.sql as $aQuery}
                                        <pre class="prettyprint lang-sql" style="word-wrap: normal;">{$aQuery.sql}</pre>
                                    {/foreach}
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="list">...</div>
        </div>

    </div>

    <script>
        $('.js-config-explode').click(function(){
            var key=$(this).data('config-key');

            $('#config-key-' + key).toggle();
            return false;
        });
    </script>
{/block}