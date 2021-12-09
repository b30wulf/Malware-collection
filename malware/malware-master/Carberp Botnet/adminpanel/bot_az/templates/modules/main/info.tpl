{if $_SESSION.user->access.main.info eq on || $_SESSION.user->access.main.edit eq on || $_SESSION.user->access.main.stat eq on}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.main.info eq on}<a href="/main/info.html">{$lang.info}</a>&nbsp;{/if}
{if $_SESSION.user->access.main.stat eq on}<a href="/main/stat.html">{$lang.stat}</a>&nbsp;{/if}
</div>
</div>
{/if}
<div style="cursor:default">
<hr /><h2 style="text-align:center">{$lang.license}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%">{$lang.bindip}:</th>
  	<th>{$core.ip}</th>
</tr>
{if $trial_end_data ne ''}
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.et}:</th>
  	<th title="{$trial_end_data|date_format:'d.m.Y H:i'}">{if $trial_end_sec ne ''}{$lang.cher} {$trial_end_sec|time_math}{else}{$trial_end_data|date_format:'d.m.Y H:i'}{/if}</th>
</tr>
{/if}
</table>
<hr /><h2 style="text-align:center">{$lang.is}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%">{$lang.os}:</th>
  	<th>{$core.os}</th>
</tr>
{if $PHP_OS ne 'WINNT'}
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.timeos}:</th>
  	<th>{$core.server_uptime}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.rame}:</th>
  	<th>{$core.server_meminfo.MemTotal|size_format} / {$core.server_meminfo.MemFreeAll|size_format} / {$core.server_meminfo.UsedAll|size_format} </th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.swap}:</th>
  	<th>{$core.server_meminfo.SwapTotal|size_format} / {$core.server_meminfo.SwapFreeAll|size_format}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.hdd}:</th>
  	<th>{$core.dts|size_format} / {$core.dfs|size_format}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.loados}:</th>
  	<th>{$core.sys_loadavg.0} / {$core.sys_loadavg.1} / {$core.sys_loadavg.2}</th>
</tr>
{if $core.sys.user}
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.system}:</th>
  	<th>{$core.sys.user} / {$core.sys.system} / {$core.sys.iowait} / {$core.sys.idle}</th>
</tr>
{/if}
{/if}
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>

<tr class="bg2">
	<th>{$lang.vwebserv}:</th>
    <th>{$smarty.server.SERVER_SOFTWARE}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width: 30%">{$lang.vphp}:</th>
    <th>{$core.phpversion}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.vzo}: </th>
    <th>{$core.ZendOptimizer}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.vioncube}: </th>
    <th>{$core.ionCubeLoader}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th>{$lang.vsmarty}:</th>
	<th>{$smarty.version}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.vgeoip}: </th>
	<th>{$core.geoip_country}</th>
</tr>
</table>

{if $eaccelerator_info}

<hr /><h2 style="text-align:center">{$lang.ea}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%;">{$lang.veacc}:</th>
	<th>{$eaccelerator_info.version}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.rame}: </th>
	<th>{$eaccelerator_info.memorySize|size_format} / {$eaccelerator_info.memoryAvailable|size_format} / {$eaccelerator_info.memoryAllocated|size_format}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.cachescr}: </th>
	<th>{$eaccelerator_info.cachedScripts}</th>
</tr>
</table>

{/if}
<hr />
<h2 style="text-align:center">{$lang.im}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%;">{$lang.vermysql}:</th>
	<th>{$core.mysql_version}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.timemysql}: </th>
	<th>{$core.mysql_uptime}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.ct}: </th>
	<th>{$core.mysql_table_count}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th nowrap="nowrap">{$lang.sdb}: </th>
	<th>{$core.mysql_all_size}</th>
</tr>
</table>
<hr /><h2 style="text-align:center">{$lang.modules}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
{foreach from=$mods item=item key=key name=mods}
{if $smarty.foreach.mods.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bg{$bg}">
	<th style="width:40%;">{$item.name}</th>
	<th>{$item.version}</th>
</tr>
{/foreach}
</table>
<hr />
<h2 style="text-align:center">{$lang.uo}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg4">
	<th style="width:40%; text-align:center">{$lang.login}</th>
	<th style="width:30%; text-align:center">{$lang.ip}</th>
	<th style="width:30%; text-align:center">{$lang.lasta}</th>
</tr>
{foreach from=$active_user item=active name=users}
{if $smarty.foreach.users.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onmousemove="this.className = 'bg4'" onmouseout="this.className = 'bg{$bg}'" style="border: 1px solid #FFFFFF">
	<th style="text-align:center"><a href="/accounts/profile-{$active->id}.html">{$active->login}</a></th>
	<th style="text-align:center"><a href="http://dig.ua/search/{$active->info->REMOTE_ADDR}" target="_blank">{$active->info->REMOTE_ADDR}</a>{if $active->info->screen} ({$active->info->screen->w}x{$active->info->screen->h}){/if}</th>
	<th style="text-align:center">{$active->expiry_date}</th>
</tr>
{/foreach}
</table>
</div>