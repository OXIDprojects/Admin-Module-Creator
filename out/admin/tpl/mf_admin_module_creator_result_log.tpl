
<div class="boxTitle">[{oxmultilang ident="MF_AMC_RESULT_LOG"}]</div>
<div class="box">
[{if $errorMessage->buildError}]
    [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->buildError}]
    [{oxmultilang ident="MF_AMC_GENERIC_HINT"}]: Das Modul wurde nicht erstellt.
[{/if}]
<pre>
[{foreach from=$log item="logEntry"}]
[<span class="mfAMC_[{$logEntry.type}]">[{$logEntry.type}]</span>] <span class="log_[{$logEntry.result}]">[{$logEntry.result}]</span>: [{$logEntry.filename}]
[{/foreach}]
</pre>
</div>
