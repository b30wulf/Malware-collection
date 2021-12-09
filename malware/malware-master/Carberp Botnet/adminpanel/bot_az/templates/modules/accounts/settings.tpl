<form action="" enctype="application/x-www-form-urlencoded" method="post">
<h2 align="center">{$lang.anp}: {$user->login}</h2>
<hr />
<br />
{if $account_errors ne ""}
<div align="center">
{$account_errors}
</div>
<br />
{/if}
<div align="center">
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%; border: 1px solid #cccccc;">
<tr class="bgp1">
  <td style="width:250px">{$lang.lang}:</td>
  <td>
  <select name="lang" class="reg_input">
  <option value="ru"{if $user->config.lang eq 'ru"'} selected="selected"{/if}>Russian</option>
  <option value="en"{if $user->config.lang eq 'en'} selected="selected"{/if}>English</option>
  </select> 
  </td>
</tr>
{if $user->access.keylog.index eq 'on'} {/if}
{if $user->access.cabs.index eq 'on'}
 {/if}
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%; border: 1px solid #cccccc;">
<tr class="bgp2">
  <td style="width:250px">Ограниченный Акк:</td>
  <td>
  <select name="infoacc" class="reg_input">
  <option value="0"{if $user->config.infoacc eq '0"'} selected="selected"{/if}>Выключено</option>
  <option value="1"{if $user->config.infoacc eq '1'} selected="selected"{/if}>Включено</option>
  </select> 
  </td>
</tr>

<tr class="bgp1">
  <td style="width:250px">Доступные системы:</td>
  <td>
  <select name="systems[]" class="reg_input" multiple="multiple" style="min-height: 220px">
  {foreach from=$systems item=item}
  <option value="{$item->nid}"{if $user->config.systems[$item->nid] eq 'true"'} selected="selected"{/if}>{$item->name} - {$item->nid}</option>
  {/foreach}
  </select> 
  </td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%; border: 1px solid #cccccc;">
<tr class="bgp2">
  <td style="width:250px">{$lang.userid}:</td>
  <td>
  <select id="userid" name="userid" class="reg_input" style="width:100%">
  <option value="">Убрать</option>
  {foreach from=$clist item=cl key=cid}
  <option value="{$cid}"{if $user->config.userid eq $cid} selected="selected"{/if}>{$cl} - {$cid}</option>
  {/foreach}
  
  </select>
  </td>
</tr>
</table>

<hr />

</div>
<hr />
<input type="submit" name="save" value="{$lang.save}" style="width:100%;" />
</form>
<hr />