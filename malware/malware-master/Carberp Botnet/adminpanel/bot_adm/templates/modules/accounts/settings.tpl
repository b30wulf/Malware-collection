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
<tr class="bgp2">
  <td style="width:250px">{$lang.pref}:</td>
  <td>
  <select id="prefix" name="prefix" class="reg_input">
  <option value="">{$lang.all}</option>
  {foreach from=$prefix item=pref}<option value="{$pref}"{if $user->config.prefix eq $pref}selected="selected"{/if}>{$pref}</option>{/foreach}
  </select>
  </td>
</tr>
<tr class="bgp1">
  <td>{$lang.akz}:</td>
  <td><input id="bots" name="cp[bots]" type="text" value="{$user->config.cp.bots}" class="reg_input" /></td>
</tr>
<tr class="bgp2">
  <td>{$lang.akz1}:</td>
  <td><input id="bots_country" name="cp[bots_country]" type="text" value="{$user->config.cp.bots_country}" class="reg_input" /></td>
</tr>
<tr class="bgp1">
  <td>{$lang.akz2}:</td>
  <td><input id="keylog" name="cp[keylog]" type="text" value="{$user->config.cp.keylog}" class="reg_input" /></td>
</tr>
<tr class="bgp2">
  <td>{$lang.akz3}:</td>
  <td><input id="keylogp" name="cp[keylogp]" type="text" value="{$user->config.cp.keylogp}" class="reg_input" /></td>
</tr>
<tr class="bgp1">
  <td>{$lang.akz4}:</td>
  <td><input id="cabs" name="cp[cabs]" type="text" value="{$user->config.cp.cabs}" class="reg_input" /></td>
</tr>
<tr class="bgp2">
  <td>{$lang.akz5}:</td>
  <td><input id="filters" name="cp[filters]" type="text" value="{$user->config.cp.filters}" class="reg_input" /></td>
</tr>
<tr class="bgp1">
  <td>{$lang.ads}:</td>
  <td><input id="jabber" name="jabber" type="text" value="{$user->config.jabber}" class="reg_input" /></td>
</tr>
<tr class="bgp2">
  <td>{$lang.auhu}:<br /><span style="font-size:10px">{$lang.aovjl}<br />{$lang.alktsb}</span></td>
  <td>
  <select id="sbbc" name="sbbc" class="reg_input">
  <option value="1"{if $user->config.sbbc eq 1} selected="selected"{/if}>{$lang.on}</option>
  <option value="0"{if $user->config.sbbc ne 1} selected="selected"{/if}>{$lang.off}</option>
  </select>
</td>
</tr>
<tr class="bgp1">
  <td>{$lang.aopb}:<br /><span style="font-size:10px">{$lang.apboztbkp}</span></td>
  <td>
  <select id="hunter_limit" name="hunter_limit" class="reg_input">
  <option value="1"{if $user->config.hunter_limit eq 1} selected="selected"{/if}>{$lang.on}</option>
  <option value="0"{if $user->config.hunter_limit ne 1} selected="selected"{/if}>{$lang.off}</option>
  </select>
</td>
</tr>
{if $user->access.keylog.index eq 'on'}
<tr class="bgp2">
  <td>{$lang.aopkl}:<br /><span style="font-size:10px">{$lang.avrkl}</span></td>
  <td>
  <select id="klimit" name="klimit[]" class="reg_input" multiple="multiple" style="height: 21{if $keylogs|@count > 0}0{/if}px">
  <option value="*"{if $user->config.klimit eq ''} selected="selected"{/if}>{$lang.off}</option>
  {foreach from=$keylogs item=kl name=kl}
  <option value="{$kl->hash}"{if $user->config.klimit[$kl->hash] ne ''} selected="selected"{/if}>{$kl->name}</option>
  {/foreach}
  </select>
</td>
</tr>
{/if}
{if $user->access.cabs.index eq 'on'}
<tr class="bgp{if $user->access.keylog.index eq 'on'}1{else}2{/if}">
  <td>{$lang.aopkl}:<br /><span style="font-size:10px">{$lang.avrcl}</span></td>
  <td>
  <select id="climit" name="climit[]" class="reg_input" multiple="multiple" style="height: 21{if $cabs|@count > 0}0{/if}px">
  <option value="*"{if $user->config.climit eq ''} selected="selected"{/if}>{$lang.off}</option>
  {foreach from=$cabs item=cl name=cl}
  <option value="{$cl->type}"{if $user->config.climit[$cl->type] ne ''} selected="selected"{/if}>{$cl->type|strtoupper}</option>
  {/foreach}
  </select>
</td>
</tr>
{/if}
</table>
</div>
<hr />
<input type="submit" name="save" value="{$lang.save}" style="width:100%;" />
</form>
<hr />