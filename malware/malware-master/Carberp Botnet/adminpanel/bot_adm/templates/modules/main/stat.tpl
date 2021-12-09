{if $_SESSION.user->access.main.info eq on || $_SESSION.user->access.main.edit eq on || $_SESSION.user->access.main.stat eq on}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.main.info eq on}<a href="/main/info.html">{$lang.info}</a>&nbsp;{/if}
{if $_SESSION.user->access.main.stat eq on}<a href="/main/stat.html">{$lang.stat}</a>&nbsp;{/if}
</div>
<div class="top_menu_right">
{if $_SESSION.user->access.main.clear_bots eq on}<a href="/main/clear_bots.html" onclick="return confirm('Вы уверены?');">{$lang.dab}</a> {/if}
{if $_SESSION.user->access.main.clear_process eq on}<a href="/main/clear_process.html" onclick="return confirm('Вы уверены?');">{$lang.dap}</a> {/if}
{if $_SESSION.user->access.main.clear_search eq on}<a href="/main/clear_search.html" onclick="return confirm('Вы уверены?');">{$lang.dzrp}</a> {/if}
{if $_SESSION.user->access.main.clear_all eq on}<a href="/main/clear_all.html"  onclick="return confirm('Вы уверены?');">{$lang.cadb}</a> {/if}
</div>
</div>
{/if}
<hr /><h2 style="text-align:center">{$lang.sb}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%">{$lang.ba}:</th>
  	<th>{$bots.all}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.onl}:</th>
  	<th>{$bots.onl} ({$bots.onlnp}%)</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.bvn}:</th>
  	<th>{$bots.new} ({$bots.allnp}%)</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.bva}:</th>
  	<th>{$bots.active} ({$bots.allap}%)</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.bz24}:</th>
  	<th>{$bots.a24} ({$bots.a24p}%)</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.bz7}:</th>
  	<th>{$bots.a7} ({$bots.a7p}%)</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width:40%">{$lang.bz1}:</th>
  	<th>{$bots.a1} ({$bots.a1p}%)</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width: 30%">{$lang.sodb}:</th>
    <th>{$db_stat.bf_bots->all_size|size_format} {$lang.iz} {$db_stat.bf_bots->Max_data_length|size_format} ({$db_stat.bf_bots->percent}%)</th>
</tr>
</table>
<hr />
<hr /><h2 style="text-align:center">{$lang.sp}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%">{$lang.sa}:</th>
  	<th>{$bots.proc_all}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width: 30%">{$lang.sodb}:</th>
    <th>{$db_stat.bf_process_stats->all_size|size_format} {$lang.iz} {$db_stat.bf_process_stats->Max_data_length|size_format} ({$db_stat.bf_process_stats->percent}%)</th>
</tr>
</table>
<hr />
<hr /><h2 style="text-align:center">{$lang.spo}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%">{$lang.az}:</th>
  	<th>{$bots.search_task} / {$bots.search_result}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width: 30%">{$lang.sdbzp}:</th>
    <th>{$db_stat.bf_search_task->all_size|size_format} {$lang.iz} {$db_stat.bf_search_task->Max_data_length|size_format} ({$db_stat.bf_search_task->percent}%) / {$db_stat.bf_search_result->all_size|size_format} {$lang.iz} {$db_stat.bf_search_result->Max_data_length|size_format} ({$db_stat.bf_search_result->percent}%)</th>
</tr>
</table>
<hr />
<h2 style="text-align:center">{$lang.sc}</h2><hr />
<table cellspacing="1" cellpadding="0" style="width:100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="width:40%">{$lang.ac}:</th>
  	<th>{$bots.country}</th>
</tr>
<tr class="bg1">
	<th colspan="2">&nbsp;</th>
</tr>
<tr class="bg2">
	<th style="width: 30%">{$lang.rdb}:</th>
    <th>{$db_stat.bf_country->all_size|size_format} {$lang.iz} {$db_stat.bf_country->Max_data_length|size_format} ({$db_stat.bf_country->percent}%)</th>
</tr>
</table>
<hr />