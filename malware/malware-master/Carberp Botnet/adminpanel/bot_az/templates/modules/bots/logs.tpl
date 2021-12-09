<div id="lp_{$bot->id}_{$rand_name}" align="center">{$logs_pages}</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="15%">{$lang.ip}</th>
    <th width="50%">{$lang.log}</th>
    <th width="10%">{$lang.version}</th>
    <th width="25%">{$lang.data}</th>
 </tr>
{foreach from=$bot->logs item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->ip}</th>
    <th>{$item->log}</th>
    <th>{$item->version}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>