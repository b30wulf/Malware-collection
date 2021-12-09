<div id="lpt_{$bot->id}_{$rand_name}" align="center">{$logs_tech_pages}</div><br />
<div id="ltp_{$bot->id}_{$rand_name}"align="center"><a href="#null" onclick="gltl(this);">{$lang.logs_clear_tech}</a></div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="50%">{$lang.log}</th>
    <th width="25%">{$lang.data}</th>
 </tr>
{foreach from=$bot->logs_tech item=item name=logs}
{if $smarty.foreach.logs_tech.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th><div style="overflow:scroll; max-width: 600px">{$item->log}<div></th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>