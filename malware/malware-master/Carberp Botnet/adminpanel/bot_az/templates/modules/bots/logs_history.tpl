<div id="lph_{$bot->id}_{$rand_name}" align="center">{$logs_history_pages}</div><br />
<div id="lhp_{$bot->id}_{$rand_name}"align="center"><a href="#null" onclick="gltlh(this);">{$lang.logs_clear_history}</a></div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="30%">{$lang.receiver}</th>
    <th width="20%">{$lang.sum}</th>
    <th width="20%">{$lang.date}</th>
    <th width="30%">{$lang.note}</th>
 </tr>
{foreach from=$bot->logs_history item=item name=logs}
{if $smarty.foreach.logs_history.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->receiver}</th>
    <th>{$item->sum}</th>
    <th>{$item->date}</th>
    <th>{$item->note}</th>
</tr>
{/foreach}
</table>