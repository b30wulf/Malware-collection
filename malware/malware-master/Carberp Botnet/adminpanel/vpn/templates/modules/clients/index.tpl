{if $_SESSION.user->config.infoacc ne '1'}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.clients.add eq on}<a href="#null" onclick="get_window('/clients/add.html?window=1', {ldelim}name:'add', widht: 800, height:370{rdelim});">{$lang.adds}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.clients.regenerate eq on}<a href="/clients/regenerate.html" onclick="return confirm('Вы уверены?\nВсе текущие пользователи больше подключится, не смогут по старым ключам!\nЭто может занять от 10 до 30 минут.');">{$lang.regenerate}</a>&nbsp;{/if}
</div>

</div>

<hr />
{/if}

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 20%; text-align: center">{$lang.name}</td>
<td style="width: 30%; text-align: center">{$lang.desc}</td>
<td style="width: 30%; text-align: center">{$lang.ip}</td>
<td style="width: 20%; text-align: center">{$lang.status}</td>
{if $_SESSION.user->access.clients.download eq on}<td style="width: 1px;">&nbsp;</td>{/if}
{if $_SESSION.user->access.clients.edit eq on}<td style="width: 1px;">&nbsp;</td><td style="width: 1px;">&nbsp;</td>{/if}
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th onclick="glc('{$item->id}');" {if $item->enable ne 1}style="color:#FF0000"{/if}>{$item->name}</th>
<th onclick="glc('{$item->id}');">{$item->desc}</th>
<th onclick="glc('{$item->id}');">{if $item->server eq '0'}Auto{else}{$servers[$item->server]}{/if}</th>
<th onclick="glc('{$item->id}');">{if $item->status eq 0}{$lang.disconnect}{elseif $item->status eq 1}{$lang.connection}{elseif $item->status eq 2}{$lang.connect}{/if}</th>
{if $_SESSION.user->access.clients.download eq on}
<th><a href="#null" onclick="dl('{$item->id}', 'enable');"><img src="/images/icons/icon_download.gif" /></a></th>
{/if}
{if $_SESSION.user->access.clients.edit eq on}
<th><a href="#null" onclick="gsp('{$item->id}', 'enable');"><img src="/images/{if $item->enable eq 1}pause{else}play{/if}.png" /></a></th>
<th><a href="#null" onclick="get_window('/clients/edit-{$item->id}.html?window=1', {ldelim}name:'edit{$item->id}', widht: 800, height:500{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
{/if}
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>