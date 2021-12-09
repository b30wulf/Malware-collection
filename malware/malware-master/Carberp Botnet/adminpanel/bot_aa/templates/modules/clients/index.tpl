<div class="top_menu">
<div class="top_menu_left"></div>
<div class="top_menu_right"><a href="javascript:void(0);" onclick="return get_window('/clients/add_domain.html?window=1', {ldelim}name:'add_domain', widht:'600'{rdelim});">Добавить домены</a>&nbsp;<a href="/clients/add.html" onclick="return get_window('/clients/add.html?window=1', {ldelim}name:'add', widht:'600'{rdelim});">Добавить клиента</a>&nbsp;</div>
</div>

{if $clients|@count gt 0}
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
<hr />

<form action="#" name="clients">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg4" style="border: 1px solid #FFFFFF">
	<th>Клиент</th>
    <th style="width:200px;">Админок</th>
    <th style="width:200px;">Серверов</th>
    <th style="width:150px;">Дата добавления</th>
    <th style="width:200px">Управление</th>
</tr>
{foreach from=$clients item=client name=clients}
{if $smarty.foreach.clients.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; font-size:11px;">
	<th>{$client->name}</th>
    <th>{$client->adm_count}</th>
    <th>{$client->server_count}</th>
    <th>{$client->post_date}</th>
    <th>
    <select name="control_{$client->id}" style="font-size: 11px; width:150px">
    <option value="add_server">1. Добавить сервер (IP)</option>
    <option value="list_server">2. Список серверов (IP)</option>
    <option value="add_domain">3. Добавить домен к серверу (IP)</option>
    <option value="list_domain">4. Список доменов (IP)</option>
    <option value="edit">5. Изменить</option>
    <option value="delete">6. Удалить из системы</option>
    </select>
    <input type="button" value="ОК" onclick="put_system({$client->id}, document.forms['clients'].elements['control_{$client->id}'].value);" style="font-size:10px;" />
    </th>
</tr>
{/foreach}
</table>
</form>


<hr />
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
{else}
<hr />
<div style="text-align:center; font-size:14px; font-weight:bold">Клиентов не найдено!</div>
<hr />
{/if}

