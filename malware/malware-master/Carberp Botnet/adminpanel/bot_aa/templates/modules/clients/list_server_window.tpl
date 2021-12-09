<form action="/clients/add_server-{$Cur.id}.html?window=1" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg4" style="border: 1px solid #FFFFFF">
    <th style="width:250px;">IP</th>
    <th>Домены</th>
    <th style="width:150px;">Дата добавления</th>
    <th style="width:200px">Управление</th>
</tr>
{foreach from=$servers item=server name=servers}
{if $smarty.foreach.servers.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; font-size:11px;">
	<th>{$server->ip}</th>
    <th>{if is_array($client->domains)}{implode('<br />', $client->domains)}{else}-{/if}</th>
    <th>{$client->post_date}</th>
    <th>
    <select name="control_{$client->id}" style="font-size: 11px; width:150px">
    <option value="add_domain_fs">1. Добавить домен к серверу (IP)</option>
    <option value="edit">2. Изменить</option>
    <option value="delete">3. Удалить из системы</option>
    </select>
    <input type="button" value="ОК" onclick="put_system_a({$server->id}, document.forms['add_sub{$rand_name}'].elements['control_{$client->id}'].value, id_{$rand_name});" style="font-size:10px;" />
    </th>
</tr>
{/foreach}
</table>

</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Список серверов "{$client->name}"';

function submit_{$rand_name}(){ldelim}
hax(document.forms['add_sub{$rand_name}'].action,
	{ldelim}
		method: document.forms['add_sub{$rand_name}'].method,
		form: 'add_sub{$rand_name}',
		id: id_{$rand_name} + '_content',
		nohistory:true,
		nocache:true,
		destroy:true,
		rc:true
	{rdelim}
)
document.getElementById(id_{$rand_name} + '_content').innerHTML = '<br /><div align="center"><img src="/images/indicator.gif" title="Загрузка" alt="Загрузка" /></div>';
{rdelim};
</script> 