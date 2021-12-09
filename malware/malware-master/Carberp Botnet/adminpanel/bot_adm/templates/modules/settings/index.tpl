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

<tr>
  <td style="width:250px;">{$lang.jb}:</td>
  <td><input name="live" type="text" value="{$smarty.post.live}" class="reg_input" /></td>
</tr>

<tr>
  <td style="width:250px;">{$lang.supd}:</td>
  <td>
    <select name="autocmd" class="reg_input" style="width: 99%">
    <option value="1"{if $smarty.post.autocmd eq 1} selected="selected"{/if}>{$lang.true}</option>
    <option value="0"{if $smarty.post.autocmd ne 1} selected="selected"{/if}>{$lang.false}</option>
    </select>
    </td>
</tr>
<!--
<tr>
  <td>{$lang.lsip}:</td>
  <td><input name="http_post_ip" type="text" value="{$smarty.post.http_post_ip}" class="reg_input" /></td>
</tr>
-->
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

<tr>
  <td >Jabber {$lang.ko} (<span style="width:250px;">Builds Traf</span>):</td>
  <td><input name="jabber[bt_tracking]" type="text" value="{$smarty.post.jabber.bt_tracking}" class="reg_input" /></td>
</tr>

<tr>
  <td >Jabber {$lang.ko} (<span style="width:250px;">Domains</span>):</td>
  <td><input name="jabber[d_tracking]" type="text" value="{$smarty.post.jabber.d_tracking}" class="reg_input" /></td>
</tr>

<tr>
  <td >{$lang.sijcf}:</td>
  <td>
  <select name="jabber[cab]" class="reg_input">
  <option value="0"{if $smarty.post.jabber.cab ne '1'} selected="selected"{/if}>{$lang.no}</option>
  <option value="1"{if $smarty.post.jabber.cab eq '1'} selected="selected"{/if}>{$lang.yes}</option>
  </select>
  </td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">

<tr>
  <td style="width:250px;">{$lang.shd}:</td>
  <td colspan="2">
  	<select name="scramb" class="reg_input">
    <option value="0"{if $smarty.post.scramb ne '1'} selected="selected"{/if}>{$lang.no}</option>
    <option value="1"{if $smarty.post.scramb eq '1'} selected="selected"{/if}>{$lang.yes}</option>
    </select>
    <div style="text-align:center; color:#F00; font-weight:bold; top: 10px;position:relative">{$lang.vopovza}</div>
  </td>
</tr>
<tr>
  <td style="width:250px;">{$lang.key}:</td>
  <td colspan="2"><input name="rc" type="text" value="{$smarty.post.rc}" class="reg_input" readonly="readonly" /></td>
</tr>
<!--
<tr>
  <td style="width:250px;">Ключ:</td>
  <td><input name="hash_key" type="text" value="{$smarty.post.hash_key}" class="reg_input" readonly="readonly" /></td>
</tr>
-->
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.plfg}:</td>
  <td colspan="2">
    <select name="getlog" class="reg_input">
      <option value="0"{if $smarty.post.getlog ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.getlog eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr>
<tr>
  <td style="width:250px;">{$lang.pli}:</td>
  <td colspan="2">
    <select name="getinj" class="reg_input">
      <option value="0"{if $smarty.post.getinj ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.getinj eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr>
<tr>
  <td style="width:250px;">{$lang.plc}:</td>
  <td colspan="2">
    <select name="getcab" class="reg_input">
      <option value="0"{if $smarty.post.getcab ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.getcab eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr>
<tr>
  <td style="width:250px;">{$lang.plk}:</td>
  <td colspan="2">
    <select name="getkl" class="reg_input">
      <option value="0"{if $smarty.post.getkl ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.getkl eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr><tr>
  <td style="width:250px;">{$lang.plbi}:</td>
  <td colspan="2">
    <select name="getibank" class="reg_input">
      <option value="0"{if $smarty.post.getibank ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.getibank eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.olf}:</td>
  <td colspan="2">
    <select name="filters" class="reg_input">
      <option value="0"{if $smarty.post.filters ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.filters eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr>
</table>

<hr />

<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.aop}:</td>
  <td colspan="2">
    <select name="autoprefix" class="reg_input">
      <option value="0"{if $smarty.post.autoprefix ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.autoprefix eq '1'} selected="selected"{/if}>{$lang.true}</option>
      </select>
    </td>
</tr>
<tr>
  <td style="width:250px;">{$lang.domain_save}:</td>
  <td colspan="2">
    <select name="domain_save" class="reg_input">
      <option value="0"{if $smarty.post.domain_save ne '1'} selected="selected"{/if}>{$lang.false}</option>
      <option value="1"{if $smarty.post.domain_save eq '1'} selected="selected"{/if}>{$lang.true}</option>
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
  <td colspan="2"><input type="text" value="http://{$smarty.server.HTTP_HOST}/login/?x={$autorizekey}" class="reg_input" readonly="readonly" /></td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.textot} hunter.html:</td>
  <td colspan="2"><textarea name="hunter" class="reg_input" style="height:50px">{$smarty.post.hunter}</textarea></td>
</tr>
<tr>
  <td style="width:250px;">{$lang.kpkonsob}:</td>
  <td colspan="2"><input name="hnocomment" type="text" value="{$smarty.post.hnocomment}" class="reg_input" /></td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.gws}:</td>
  <td colspan="2"><input name="gws" type="text" value="{$smarty.post.gws}" class="reg_input" /></td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">{$lang.ips}:</td>
  <td colspan="2"><input name="ips" type="text" value="{$smarty.post.ips}" class="reg_input" /></td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">Scan4u Script:</td>
  <td colspan="2"><input name="scan4u" type="text" value="{$smarty.post.scan4u}" class="reg_input" /></td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">Domain scan4you.net ID:</td>
  <td colspan="2"><input name="d_scan4you_id" type="text" value="{$smarty.post.d_scan4you_id}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">Domain scan4you.net Token:</td>
  <td colspan="2"><input name="d_scan4you_token" type="text" value="{$smarty.post.d_scan4you_token}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">Builds scan4you.net ID:</td>
  <td colspan="2"><input name="b_scan4you_id" type="text" value="{$smarty.post.b_scan4you_id}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">Builds scan4you.net Token:</td>
  <td colspan="2"><input name="b_scan4you_token" type="text" value="{$smarty.post.b_scan4you_token}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">Builds chk4me.com Token:</td>
  <td colspan="2"><input name="b_chk4me_token" type="text" value="{$smarty.post.b_chk4me_token}" class="reg_input" /></td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">Domain:</td>
  <td colspan="2"><input name="domain_link" type="text" value="{$smarty.post.domain_link}" class="reg_input" /></td>
</tr>
<tr>
  <td style="width:250px;">Domains Limit:</td>
  <td colspan="2">
  <select name="domain_limit" class="reg_input" style="width: 100%">
  <option value="1" {if $smarty.post.domain_limit eq '1'}selected="selected"{/if}>1</option>
  <option value="2" {if $smarty.post.domain_limit eq '2'}selected="selected"{/if}>2</option>
  <option value="3" {if $smarty.post.domain_limit eq '3'}selected="selected"{/if}>3</option>
  <option value="4" {if $smarty.post.domain_limit eq '4'}selected="selected"{/if}>4</option>
  <option value="5" {if $smarty.post.domain_limit eq '5'}selected="selected"{/if}>5</option>
  <option value="6" {if $smarty.post.domain_limit eq '6'}selected="selected"{/if}>6</option>
  <option value="7" {if $smarty.post.domain_limit eq '7'}selected="selected"{/if}>7</option>
  <option value="8" {if $smarty.post.domain_limit eq '8'}selected="selected"{/if}>8</option>
  <option value="9" {if $smarty.post.domain_limit eq '9'}selected="selected"{/if}>9</option>
  <option value="10" {if $smarty.post.domain_limit eq '10'}selected="selected"{/if}>10</option>
  </select>
  </td>
</tr>
</table>
<hr />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:100%">
<tr>
  <td style="width:250px;">Domain AutoExec:</td>
  <td colspan="2">
  <select name="domains_start" class="reg_input">
  <option value="0"{if $smarty.post.domains_start ne '1'} selected="selected"{/if}>{$lang.false}</option>
  <option value="1"{if $smarty.post.domains_start eq '1'} selected="selected"{/if}>{$lang.true}</option>
  </select>
  </td>
</tr>
<tr>
  <td style="width:250px;">Builds AutoExec:</td>
  <td colspan="2">
  <select name="builds_start" class="reg_input">
  <option value="0"{if $smarty.post.builds_start ne '1'} selected="selected"{/if}>{$lang.false}</option>
  <option value="1"{if $smarty.post.builds_start eq '1'} selected="selected"{/if}>{$lang.true}</option>
  </select>
  </td>
</tr>
</table>
<hr />
<input type="submit" name="save" value="{$lang.save}" style="width:100%;" />
</form>
<hr />