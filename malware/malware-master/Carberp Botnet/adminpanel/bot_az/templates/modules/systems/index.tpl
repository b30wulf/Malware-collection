{if $_SESSION.user->config.infoacc ne '1'}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.systems.add eq on}<a href="#null" onclick="get_window('/systems/add.html?window=1', {ldelim}name:'add', widht: 800, height:420{rdelim});">{$lang.add}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">

</div>

</div>

<hr />
{/if}


<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 30%; text-align: center">{$lang.nida}</td>
<td style="width: 60%; text-align: center">{$lang.name}</td>
<td style="width: 10%; text-align: center">{$lang.percent}</td>
{if $_SESSION.user->access.systems.edit eq on}<td style="width: 1px;">&nbsp;</td>{/if}
{if $_SESSION.user->access.systems.del eq on}<td style="width: 1px;">&nbsp;</td>{/if}
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th>{$item->nid}</th>
<th>{$item->name}</th>
<th>{$item->percent}%</th>
{if $_SESSION.user->access.systems.edit eq on}
<th><a href="#null" onclick="get_window('/systems/edit-{$item->id}.html?window=1', {ldelim}name:'edit{$item->id}', widht: 800, height:420{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>{/if}
{if $_SESSION.user->access.systems.del eq on}
<th><a href="/systems/del-{$item->id}.html" onclick="return confirm('{$lang.delyb}');"><img src="/images/delete.png" alt="{$lang.adel}" /></a></th>
{/if}
</tr>
{/foreach}
</table>



<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>