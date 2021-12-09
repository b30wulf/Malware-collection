<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.keylog.prog_add eq on}<a href="#" onclick="get_window('/keylog/prog_add.html?window=1', {ldelim}name:'prog_add', widht: 800{rdelim});">{$lang.addprog}</a>&nbsp;{/if}
{if $Cur.y eq '1'}<div style="position:absolute; top:38px; left:200px; color:#F8F8F8; font-size:24px" align="center">{$lang.trash}</div>{/if}
</div>

<div class="top_menu_right">
{if $Cur.y ne '1'}
<a href="#" onclick="get_hax({ldelim}url: '/keylog/index.html?y=1&ajax=1', name:'trash', widht: 800{rdelim});">{$lang.trash}</a>
{else}
<a href="#" onclick="get_hax({ldelim}url: '/keylog/index.html?ajax=1&y=1&str=cleartrash'{rdelim});" style="background-color:#C41C1C">{$lang.trasclear}</a>
<a href="#" onclick="get_hax({ldelim}url: '/keylog/index.html?ajax=1'{rdelim});" style="background-color:#0A8200">{$lang.trashext}</a>
<div style="position:absolute; top:38px; color:#F8F8F8; right:200px; font-size:24px" align="center">{$lang.trash}</div>
{/if}
</div>

</div>
<hr />

<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.maxstr}: {$_SESSION.user->config.cp.keylog}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsego}: {$counts}</div>
</div>
<br />

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{if $Cur.y ne '1'}
<tr class="bgp3">
<td style="width: 60%; text-align: center">{$lang.name}</td>
<td style="width: 20%; text-align: center">{$lang.kolbot}</td>
<td style="width: 20%; text-align: center">{$lang.zapis}</td>
<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="keylog_list('{$item->id}', '{$Cur.y}');" title="{$item->hash}">{$item->name}</th>
<th onclick="keylog_list('{$item->id}', '{$Cur.y}');">{$item->count_bot}</th>
<th onclick="keylog_list('{$item->id}', '{$Cur.y}');">{$item->count}</th>
<th><a href="#" onclick="get_window('/keylog/prog_edit-{$item->id}.html?window=1', {ldelim}name:'prog_edit{$item->id}', widht: 800{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
<th><a href="/keylog/prog_del-{$item->id}.html?y={$Cur.y}" onclick="return confirm('{$lang.delyb}');"><img src="/images/delete.png" alt="{$lang.adel}" /></a></th>
</tr>
{/foreach}
{else}
<tr class="bgp3">
<td style="width: 60%; text-align: center">{$lang.name}</td>
<td style="width: 20%; text-align: center">{$lang.kolbot}</td>
<td style="width: 20%; text-align: center">{$lang.zapis}</td>
<td style="width: 1px;">&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="keylog_list('{$item->id}', '{$Cur.y}');" title="{$item->hash}">{$item->name}</th>
<th onclick="keylog_list('{$item->id}', '{$Cur.y}');">{$item->count_bot}</th>
<th onclick="keylog_list('{$item->id}', '{$Cur.y}');">{$item->count}</th>
<th><a href="#" onclick="get_window('/keylog/prog_edit-{$item->id}.html?window=1', {ldelim}name:'prog_edit{$item->id}', widht: 800{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
</tr>
{/foreach}
{/if}
</table>



<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>