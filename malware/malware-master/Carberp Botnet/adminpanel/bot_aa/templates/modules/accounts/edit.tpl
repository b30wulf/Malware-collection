{if $save eq ''}
<form action="" enctype="application/x-www-form-urlencoded" method="post" onsubmit="this.elements['info'].value = save_info();">
<h2>Изменение пользователя {$user->login|ucfirst} <span style="font-size:14px;">({$user->email})</span></h2>
{if $account_errors ne ""}
<div align="center">
{$account_errors}
</div>
<br />
{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Логин</th>
    <th style="text-align: left;"><input id="login_name" name="login" type="text" value="{if $smarty.post.login eq ''}{$user->login}{else}{$smarty.post.login}{/if}" class="user" readonly="readonly" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Пароль</th>
    <th style="text-align: left;"><input id="password" name="password" type="password" value="{$smarty.post.password}" class="user" /></th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">Повтор пароля</th>
    <th style="text-align: left;"><input id="password_dbl" name="pass_dbl" type="password" value="{$smarty.post.pass_dbl}" class="user" /></th>
</tr>
<tr class="bgp2">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
    <th colspan="2"><input id="edit_submit" name="edit_submit" type="submit" value="Изменить" class="user" /></th>
</tr>
</table>
</form>
{else}
<center><h2>Данные для <span style="font-size:14px;">{$user->email}</span> изменены!</h2></center>
{/if}