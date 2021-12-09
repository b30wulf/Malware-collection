<div class="top_menu">

<div class="top_menu_left">
<a href="/bots/index.html" onclick="return get_hax({ldelim}url:'/bots/index.html?ajax=1',method:'get',id:'content'{rdelim});">{$lang.nksc}</a>&nbsp;
</div>

<div class="top_menu_right">
<a href="#" href="#" onclick="get_window('/bots/p2p_config.html?window=1', {ldelim}name:'pgt', widht: 800{rdelim});">{$lang.addhots}</a>&nbsp;
</div>

</div>
<hr />
<br />
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.mbns}: {$_SESSION.user->config.cp.bots_country}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsbo}: {$counts.alls}</div>
</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 30%; text-align: center">{$lang.pref}</td>
<td style="width: 20%; text-align: center">{$lang.ip}:{$lang.port}</td>
<td style="width: 25%; text-align: center">{$lang.datacfg}</td>
<td style="width: 25%; text-align: center">{$lang.posken}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="get_bot_window('{$item->id}');" title="{$item->uid}">{$item->prefix}</th>
<th onclick="get_bot_window('{$item->id}');">{$item->ip}:{$item->port}</th>
<th onclick="get_bot_window('{$item->id}');">{$item->send_date|@TimeStampToStr}</th>
<th onclick="get_bot_window('{$item->id}');">{$item->post_date|@TimeStampToStr}</th>
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>