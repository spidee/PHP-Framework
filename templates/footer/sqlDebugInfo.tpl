<div id="SQLDebugInfo">
    {foreach from=$AllSQLQueries item="Query"}
    <div id="QueryInfo">
        <div id="Query">
            {$LANGUAGE_HIGHLIGHTER->parseMySQL($Query->getSqlQuery())}
        </div>
        
        
        
        {if $Query->debugInfo->duration}
        <div id="QueryTime">
            Query time: <span onmouseover="addDebugBubble(this, '<table id=\'QueryTimeBubble\' cellspacing=\'0\' cellpadding=\'0\'><tr><th style=\'text-align:left\'>Status</th><th style=\'\'>Duration</th><th style=\'text-align:left\'>Percentage</th></tr>{foreach from=$Query->debugInfo->profiling item="Profile"}<tr><td style=\'text-align:left\'>{$Profile->Status}</td><td>{$Profile->Duration}s</td><td>{$Profile->Percentage}</td></tr>{/foreach}</table>')">{$Query->debugInfo->duration}s</span>
        </div>
        {/if}
        
        {if $Query->debugInfo->describe}
        <div id="DescribeInfo">        
            <table cellpadding="0" cellspacing="0">
                <tr>
                    {foreach from=$Query->debugInfo->describe item="Desc" key="DescKey"}
                        <th>{$DescKey}</th>
                    {/foreach}
                </tr>
                <tr>
                    {foreach from=$Query->debugInfo->describe item="Desc" key="DescKey"}
                        <td>{$Desc}</td>
                    {/foreach}
                </tr>
            </table>
        </div>
        {/if}
    </div>
    {/foreach}
</div>