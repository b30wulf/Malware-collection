<form action="" enctype="application/x-www-form-urlencoded" method="post">
<h2 align="center">{$lang.settings}</h2>
<hr />
{if $account_errors ne ""}
<div align="center">
{$account_errors}
</div>
<br />
{/if}

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.lang}:</td>
  <td>
  <select name="lang" class="reg_input" style="width: 99%">
  <option value="ru"{if $smarty.post.lang eq 'ru"'} selected="selected"{/if}>Russian</option>
  <option value="en"{if $smarty.post.lang eq 'en'} selected="selected"{/if}>English</option>
  </select> 
  </td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.ip}:</td>
  <td><input name="ip" type="text" value="{$smarty.post.ip}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">{$lang.port}:</td>
  <td><input name="port" type="text" value="{$smarty.post.port}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">{$lang.esa}:</td>
  <td><input name="esa" type="text" value="{$smarty.post.esa}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">{$lang.portm}:</td>
  <td><input name="portm" type="text" value="{$smarty.post.portm}" class="reg_input" /></td>
</tr>
</table>

<hr />

<input type="submit" name="save" value="{$lang.save}" style="width:100%;" />

<hr />

</form>
<hr />