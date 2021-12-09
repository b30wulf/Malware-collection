<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.infod} {$logs.0->prefix}{$logs.0->uid}';
</script>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="10%">{$lang.ip}</th>
    <th width="40%">{$lang.log}</th>
    <th width="10%">{$lang.version}</th>
    <th width="15%">{$lang.system}</th>
    <th width="25%">{$lang.data}</th>
 </tr>
{foreach from=$logs item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->ip}</th>
    <th>{urldecode($item->log)}</th>
    <th>{$item->version}</th>
    <th>{$systems[$item->system]}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>