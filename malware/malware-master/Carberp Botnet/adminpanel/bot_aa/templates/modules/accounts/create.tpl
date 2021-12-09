<hr /><h2 align="center">Добавление нового пользователя</h2><hr />
{if $errors ne ""}
<div align="center">{$errors}</div><hr />
{/if}
<form action="" enctype="application/x-www-form-urlencoded" method="post" name="create_user">
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%;border: 1px solid #cccccc;">
<tr class="bgp2">
  <td style="width:150px;">Логин:</td>
  <td><input id="login_name" name="login" type="text" value="{$smarty.post.login}" class="reg_input_obligatory" /></td>
</tr>
<tr class="bgp1">
  <td>Пароль:</td>
  <td><input id="password" name="password" type="password" value="{$smarty.post.password}" class="reg_input_obligatory" /></td>
</tr>
<tr class="bgp2">
  <td>Повтор пароля:</td>
  <td><input id="password_dbl" name="pass_dbl" type="password" value="{$smarty.post.pass_dbl}" class="reg_input_obligatory" /></td>
</tr>
<tr class="bgp1">
  <td>Е-маил:</td>
  <td><input id="email" name="email" type="text" value="{$smarty.post.email}" class="reg_input_obligatory" /></td>
</tr>

<tr class="bgp2">
  <td colspan="2">
  <h3 align="center">Личные права доступа <input name="all_{$r_key}" type="button" value="±" onclick="right('', 'create_user')" style="font-size: 10px; height: 18px; line-height: 16px" /></h3>
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{foreach from=$rights item=r_value key=r_key name="r"}
<tr>
<th class="bgp3" style="width:100%; text-align:center">{$r_value.$r_key}</th>
<th class="bgp3" style="text-align:center"><input name="all_{$r_key}" type="button" value="±" onclick="right('{$r_key}', 'create_user')" style="font-size: 10px; height: 18px;"  /></th>
</tr>
{foreach from=$r_value item=i_value key=i_key name="i"}
{if $smarty.foreach.i.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr style="cursor:pointer;" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" onclick="check(document.forms['create_user'].elements['rights[{$r_key}][{$i_key}]'])">
		<th style="width:100%; text-align:left;">{if $i_key eq 'index'}Глобальный доступ:{else}Доступ:{/if} {$i_value}</th>
		<th valign="middle"><input name="rights[{$r_key}][{$i_key}]" type="checkbox" {if $smarty.post.rights.$r_key.$i_key eq 'on'}checked="checked"{/if} onclick="check(this)" /></th>
</tr>
{/foreach}
{/foreach}
</table>
  </td>
</tr>
<tr class="bgp1">
  <td colspan="2"><input id="reg_submit" name="reg_submit" type="submit" value="Добавить" style="width:100%" /></td>
</tr>
</table>
</form>