</div>

<div class="actions">
[{strip}]
    <ul>
        [{include file="bottomnavicustom.tpl"}]
        [{ if $sHelpURL }]
        [{* HELP *}]
        <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.help" href="[{ $sHelpURL }]/[{ $shop->cl|oxlower }].html" OnClick="window.open('[{ $sHelpURL }]/[{ $shop->cl|lower }].html','OXID_Help','width=800,height=600,resizable=no,scrollbars=yes');return false;">[{ oxmultilang ident="TOOLTIPS_OPENHELP" }]</a></li>
        [{/if}]
    </ul>
    <div class="mfFloatRight">
        <ul>
            <li class="mfAMCVersion">Version [{$oView->getVersion()}]</li>
        </ul>
    </div>
[{/strip}]
</div>