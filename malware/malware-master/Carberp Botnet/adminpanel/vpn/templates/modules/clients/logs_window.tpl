<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.name}: {$client->name}';
</script>
<table cellspacing="1" cellpadding="3" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="15%">{$lang.nip}</th>
    <th width="15%">{$lang.rip}</th>
    <th width="30%">{$lang.sent} / {$lang.received}</th>
    <th width="30%">{$lang.data}</th>
</tr>
{foreach from=$logs item=item name=transfers}
{if $smarty.foreach.transfers.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->log->nip}</th>
    <th>{$item->log->ip}</th>
    <th>{$item->log->sent|size_format} / {$item->log->received|size_format}</th>
    <th>{$item->log->timec|ts2str}</th>
</tr>
{/foreach}
</table>