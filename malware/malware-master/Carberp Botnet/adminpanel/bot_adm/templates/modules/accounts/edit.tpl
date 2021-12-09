{if $save eq ''}
<form action="" enctype="application/x-www-form-urlencoded" method="post" onsubmit="this.elements['info'].value = save_info();">
<div align="center"><h2>{$lang.aizp} {$user->login|ucfirst}</h2></div>
{if $account_errors ne ""}
<div align="center">
{$account_errors}
</div>
<br />
{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">{$lang.login}</th>
    <th style="text-align: left;"><input id="login_name" name="login" type="text" value="{if $smarty.post.login eq ''}{$user->login}{else}{$smarty.post.login}{/if}" class="user" readonly="readonly" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.pass}</th>
    <th style="text-align: left;"><input id="password" name="password" type="password" value="{$smarty.post.password}" class="user" /></th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.rpass}</th>
    <th style="text-align: left;"><input id="password_dbl" name="pass_dbl" type="password" value="{$smarty.post.pass_dbl}" class="user" /></th>
</tr>
<tr class="bgp2">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
    <th colspan="2"><input id="edit_submit" name="edit_submit" type="submit" value="{$lang.edit}" class="user" /></th>
</tr>
</table>
</form>
{else}
<center><h2>{$lang.adiz}</h2></center>
{/if}