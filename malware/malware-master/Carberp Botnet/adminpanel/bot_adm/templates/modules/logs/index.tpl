<div class="top_menu">

<div class="top_menu_left">
<span style="font-size: 22px; color:#CCCCCC">{$lang.logs}</span>
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.logs.screen eq on}<a href="/logs/screen.html">{$lang.lscreen}</a>&nbsp;{/if}
</div>

</div>
<hr />

<form action="#" method="post" enctype="multipart/form-data" name="filter" id="filter">

<div style="text-align: right; padding-right: 10px; padding-left: 10px;">
<span style="top: -40px; position:relative">{$lang.cdate} (<a onclick="alert('{$lang.cdate_desc}');" style="cursor:pointer">?</a>): </span><select id="data" name="date[]" style="width:600px; height: 100px;" multiple="multiple">
<option value="ALL">{$lang.svd}</option>
{foreach from=$dt item=d key=k}
<option value="{$k}"{if $dts[$k] eq true} selected="selected"{/if}>{$d}</option>
{/foreach}
</select>
<hr />
{$lang.puid} (<a onclick="alert('{$lang.puid_desc}');" style="cursor:pointer">?</a>): <input name="puid" type="text" style="width:600px" value="{$_SESSION.search.logs.puid}"/>
<hr />
{$lang.ctype} (<a onclick="alert('{$lang.ctype_desc}');" style="cursor:pointer">?</a>): <select id="type" name="type" style="width:600px;">
<option value="">{$lang.all}</option>
<option value="1"{if $_SESSION.search.logs.type eq '1'} selected="selected"{/if}>{$lang.fgr}</option>
<option value="2"{if $_SESSION.search.logs.type eq '2'} selected="selected"{/if}>{$lang.inj}</option>
<option value="3"{if $_SESSION.search.logs.type eq '3'} selected="selected"{/if}>{$lang.gra}</option>
<option value="4"{if $_SESSION.search.logs.type eq '4'} selected="selected"{/if}>{$lang.sni}</option>
</select>
<hr />
{$lang.country} (<a onclick="alert('{$lang.country_desc}');" style="cursor:pointer">?</a>): <select id="country" name="country" style="width:600px">
<option value="ALL">{$lang.all}</option>
{foreach from=$country item=d}
<option value="{$d->country}"{if $_SESSION.search.logs.country eq $d->country} selected="selected"{/if}>{$d->country}</option>
{/foreach}
</select>
<hr />
{$lang.cip} (<a onclick="alert('{$lang.cip_desc}');" style="cursor:pointer">?</a>): <input name="ip" type="text" style="width:600px" value="{$_SESSION.search.logs.ip}"/>
<hr />
{$lang.curl} (<a onclick="alert('{$lang.curl_desc}');" style="cursor:pointer">?</a>): <input name="url" type="text" style="width:600px" value="{$_SESSION.search.logs.url}"/>
<hr />
{$lang.cdata} (<a onclick="alert('{$lang.cdata_desc}');" style="cursor:pointer">?</a>): <input name="data" type="text" style="width:600px" value="{$_SESSION.search.logs.data}"/>
<hr />
<input type="button" name="search" value="{$lang.search}" style="width:100%" onclick="return get_hax({ldelim}url: '/logs/index.html?ajax=1',method:'post',id:'content',form:'filter'{rdelim});" />
</div>
</form>

{if $logs}
<form action="#" name="groups" >
<br />{include file='modules/logs/date.tpl'}<br />
</form>
{/if}