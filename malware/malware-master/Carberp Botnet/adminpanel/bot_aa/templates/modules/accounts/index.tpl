<div class="top_menu">

<div class="top_menu_left">
<a href="/accounts/create.html" onclick="">Создать пользователя</a>
</div>

<div class="top_menu_right">
Всего пользователей: {$count_users}
</div>

</div>
<hr />

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="text-align: center">#</td>
	<td style="width: 40%; text-align: center">Логин</td>
	<td style="width: 40%; text-align: center">Емаил</td>
	<td style="width: 20%; text-align: center">Дата регистрации</td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
{foreach from=$users item=user name=users}
{if $smarty.foreach.users.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF">
	<th>{$user->id}</th>
	<th style="cursor:pointer;" onclick="location.href = '/accounts/profile-{$user->id}.html'">{$user->login|ucfirst}</th>
	<th style="cursor:pointer;" onclick="location.href = '/accounts/profile-{$user->id}.html'">{$user->email}</th>
	<th style="cursor:pointer;" onclick="location.href = '/accounts/profile-{$user->id}.html'">{$user->post_date}</th>
	<th><a href="/accounts/settings-{$user->id}.html"><img src="/images/modules/accounts/usrcfg.png" alt="Настройки" /></a></th>
	<th><a href="/accounts/rights-{$user->id}.html"><img src="/images/modules/accounts/usrrights.png" alt="Права доступа" /></a></th>
	<th><a href="/accounts/edit-{$user->id}.html"><img src="/images/modules/accounts/usredit.png" alt="Редактирование" /></a></th>
	<th><a href="/accounts/delete-{$user->id}.html" onclick="return confirm('Действительно удалить?');"><img src="/images/modules/accounts/usrdrop.png" alt="Удаление" /></a></th>
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>