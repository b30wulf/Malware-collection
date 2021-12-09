{if $_SESSION.user->config.infoacc ne '1'}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.servers.add eq on}<a href="#null" onclick="get_window('/servers/add.html?window=1', {ldelim}name:'add', widht: 800, height:660{rdelim});">{$lang.adds}</a>&nbsp;{/if}
{if $_SESSION.user->access.servers.auto_install eq on}<a href="#null" onclick="get_window('/servers/auto_install.html?window=1', {ldelim}name:'auto_install', widht: 800, height:400{rdelim});">{$lang.auto_install}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">
<a href="#null" onclick="update_prio();">{$lang.update_prio}</a>&nbsp;
</div>

</div>

<hr />
{/if}

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 1px;">&nbsp;</td>
<td style="width: 40%; text-align: center">{$lang.name}</td>
<td style="width: 30%; text-align: center">{$lang.ip}</td>
<td style="width: 30%; text-align: center">{$lang.status}</td>
{if $_SESSION.user->config.infoacc ne '1'}
<td colspan="2" style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
{/if}
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF;">
<th>{$item->id}</th>
<th title="{$item->desc}">{$item->name}</th>
<th>{$item->ip}</th>
<th>{if $item->status eq 0}Не работает{elseif $item->status eq 1}Запуск{elseif $item->status eq 2}Работает{/if}</th>
{if $_SESSION.user->config.infoacc ne '1'}
<th>{if $prio->min ne $item->prio}<a href="#null" onclick="gsp('{$item->id}', 'up');"><img src="/images/icons/arrow_up.gif" /></a>{else}&nbsp;{/if}</th>
<th>{if $prio->max ne $item->prio}<a href="#null" onclick="gsp('{$item->id}', 'down');"><img src="/images/icons/arrow_down.gif" /></a>{else}&nbsp;{/if}</th>
<th><a href="#null" onclick="gsp('{$item->id}', 'enable');"><img src="/images/{if $item->enable eq 1}pause{else}play{/if}.png" /></a></th>
<th><a href="#null" onclick="get_window('/servers/edit-{$item->id}.html?window=1', {ldelim}name:'edit{$item->id}', widht: 800, height:500{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
<th><a href="/servers/del-{$item->id}.html" onclick="return confirm('{$lang.delyb}');"><img src="/images/delete.png" alt="{$lang.adel}" /></a></th>
{/if}
</tr>
{/foreach}
</table>



<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>