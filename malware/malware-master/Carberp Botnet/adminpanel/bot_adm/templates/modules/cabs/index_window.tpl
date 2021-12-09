<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.scren} #{$Cur.x}';
</script>
{if $items|@count > 0}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bgp{$bg}" style="font-size:10px">
    <th>{$lang.nfa}</th>
    <th style="width:30%;">{$lang.raz}</th>
    <th style="width:30%;">{$lang.date}</th>
    <th style="width:1px;"></th>
    <th style="width:1px;"></th>
    <th style="width:1px;"></th>
</tr>
{foreach from=$items item=item name=items}
{if $smarty.foreach.items.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
{if $item->ready eq 1}
<tr class="bgp{$bg}">
    <th><a href="/logs/download-9.html?file={$item->file}" target="_blank">{$item->file}</a></th>
    <th>{$item->size|size_format}</th>
    <th>{$item->post_date}</th>
    <th><a href="/cabs/cab_view-{$item->id}.html?str=text" target="_blank"><img src="/images/icons/page_text.gif" title="{$lang.cvte}" border="0" /></a></th>
    <th><a href="/cabs/cab_view-{$item->id}.html?str=img" target="_blank"><img src="/images/icons/image.gif" title="{$lang.cvim}" border="0" /></a></th>
    <th><a href="#" onclick="delete_item('{$item->id}', id_{$rand_name}, '{$_SESSION.ctype}');"><img src="/images/delete.png" title="{$lang.del}" border="0" /></a></th>
</tr>
{else}
<tr class="bgp{$bg}">
    <th>{$lang.load} ({number_format($item->partc / $item->parts * 100, 2)}%)</th>
    <th>{$item->size|size_format}</th>
    <th>{$item->post_date}</th>
    <th colspan="3">&nbsp;</th>
</tr>
{/if}
{/foreach}
</table>
{else}
<hr />
<h2 align="center">{$lang.notfound}</h2>
<hr />
{/if}