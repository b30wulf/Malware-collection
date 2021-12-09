<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.transfers.manual_add eq on}<a href="#null" onclick="get_window('/transfers/manual_add.html?window=1', {ldelim}name:'manual_add', widht: 800, height:600{rdelim});">Добавить конфиг</a>&nbsp;{/if}
</div>

<div class="top_menu_right">{if $_SESSION.user->access.transfers.index eq on}<a href="/transfers/index.html">Назад</a>&nbsp;{/if}</div>

</div>

<hr />
<form action="#" method="post" enctype="multipart/form-data" name="manual_filter" id="manual_filter" target="_blank">

<div style="text-align: right; padding-right: 10px; padding-left: 10px">

Система: <select id="sys" name="sys" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$sys key=key item=item}
<option value="{$key}"{if $_SESSION.search.manual.sys eq $key} selected="selected"{/if}>{$item}</option>
{/foreach}
</select>
<hr />
Дата: <select id="date" name="date" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$date key=key item=item}
<option value="{$key}"{if $_SESSION.search.manual.date eq $key} selected="selected"{/if}>{$key}</option>
{/foreach}
</select>
<hr />
Счет: <input name="acc" type="text" style="width:600px" value="{$_SESSION.search.manual.acc}"/>
<hr />
<input type="button" name="update" value="Обновить" style="width:100%" onclick="return get_hax({ldelim}url: '/transfers/manual.html?ajax=1',method:'post',id:'content',form:'manual_filter'{rdelim});" />
</div>
</form>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 60%; text-align: center">{$lang.system}</td>
<td style="width: 20%; text-align: center">{$lang.post_date}</td>
<td style="width: 20%; text-align: center">{$lang.link} (дата окончания)</td>
<td style="width: 1px;">&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF;">
<th>{$item->system|strtoupper}</th>
<th>{$item->post_date}</th>
<th>{if $item->expiry_date eq '0000-00-00 00:00:00'}<a onclick="gli(this, '{$item->id}');">Создать</a>{else}{$item->link}{/if}</th>
<th><a href="/transfers/manual-{$item->id}.html?str=delete" onclick="return confirm('Действительно удалить?');"><img src="/images/delete.png" /></a></th>
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

