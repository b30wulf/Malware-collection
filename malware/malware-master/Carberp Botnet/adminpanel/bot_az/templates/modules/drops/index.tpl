<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.drops.add eq on}<a href="#null" onclick="get_window('/drops/add.html?window=1', {ldelim}name:'add', widht: 800, height:400{rdelim});">{$lang.adds}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">

</div>

</div>

<hr />

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

{if $_SESSION.user->userid eq ''}

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 20%; text-align: center">{$lang.name}</td>
<td style="width: 40%; text-align: center">{$lang.receiver}</td>
<td style="width: 20%; text-align: center">{$lang.from} / {$lang.to}</td>
<td style="width: 10%; text-align: center">UserID</td>
<td style="width: 10%; text-align: center">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="gdw('{$item->id}');">{$item->name}</th>
<th onclick="gdw('{$item->id}');">{$item->receiver}</th>
<th onclick="gdw('{$item->id}');">{$item->from} / {$item->to}</th>
<th onclick="gdw('{$item->id}');" title="{$item->userid}">{$item->userid|cnuid}</th>
<th onclick="gdw('{$item->id}');">{$item->count}</th>
<th><a href="#null" onclick="enable('{$item->id}');"><img src="/images/{if $item->status eq 0}pause{else}play{/if}.png" /></a></th>
<th><a href="#null" onclick="get_window('/drops/edit-{$item->id}.html?window=1', {ldelim}name:'edit{$item->id}', widht: 800, height:500{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
<th><a href="/drops/del-{$item->id}.html" onclick="return confirm('{$lang.delyb}');"><img src="/images/delete.png" alt="{$lang.adel}" /></a></th>
</tr>
{/foreach}
</table>

{else}

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 20%; text-align: center">{$lang.name}</td>
<td style="width: 40%; text-align: center">{$lang.receiver}</td>
<td style="width: 20%; text-align: center">{$lang.from} / {$lang.to}</td>
<td style="width: 10%; text-align: center">&nbsp;</td>

<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
<td style="width: 1px;">&nbsp;</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="gdw('{$item->id}');">{$item->name}</th>
<th onclick="gdw('{$item->id}');">{$item->receiver}</th>
<th onclick="gdw('{$item->id}');">{$item->from} / {$item->to}</th>
<th onclick="gdw('{$item->id}');">{$item->count}</th>
<th><a href="#null" onclick="enable('{$item->id}');"><img src="/images/{if $item->status eq 0}pause{else}play{/if}.png" /></a></th>
<th><a href="#null" onclick="get_window('/drops/edit-{$item->id}.html?window=1', {ldelim}name:'edit{$item->id}', widht: 800, height:500{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
<th><a href="/drops/del-{$item->id}.html" onclick="return confirm('{$lang.delyb}');"><img src="/images/delete.png" alt="{$lang.adel}" /></a></th>
</tr>
{/foreach}
</table>

{/if}





<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>