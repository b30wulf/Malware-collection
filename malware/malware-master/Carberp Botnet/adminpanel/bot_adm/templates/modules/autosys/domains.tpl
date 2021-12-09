<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.autosys.domains eq on}<a href="/autosys/domains.html" id="current">{$lang.domains}</a>&nbsp;{/if}
{if $_SESSION.user->access.autosys.builds eq on}<a href="/autosys/builds.html">{$lang.builds}</a>&nbsp;{/if}
{if $check_pid eq false}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/autosys/domains.html?str=start_builds">{$lang.start_builds}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">
{if $_SESSION.user->access.autosys.domains_add eq on}<a href="#" onclick="get_window('/autosys/domains_add.html?window=1', {ldelim}name:'domains_add', widht: 800{rdelim});">{$lang.domains_add}</a>&nbsp;{/if}
</div>

</div>

<hr /><h2 style="text-align:center">{$lang.dcfb}</h2><hr />
<form action="#" name="groups" >
<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 20%;">{$lang.domain}</th>
	<th style="width: 20%;">{$lang.stus}</th>
    <th style="width: 20%;">{$lang.av}</th>
    <th style="width: 20%;">{$lang.comm}</th>
    <th style="width: 20%;">{$lang.update}</th>
	<th style="width: 2%;"></th>
</tr>
{foreach from=$domains item=item name=domains}
{if $smarty.foreach.domains.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}">
    <td>{$item->host}</td>
	<td>{if $item->status eq '1'}{$lang.std}{elseif $item->status eq '2'}{$lang.szpt}{else}{$lang.snpr}{/if}</td>
    <td><a href="#null" onclick="get_window('/autosys/domains.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc}</a></td>
    <th style="cursor:pointer; {if $item->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$item->id}" ondblclick="ecd(this, 'groups');">{$item->comment}</th>
    <td>{$item->up_date}</td>    
	<td><a href="#" onclick="delete_domain('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
</table>

<hr /><h2 style="text-align:center">{$lang.dcfc}</h2><hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 20%;">{$lang.domain}</th>
	<th style="width: 20%;">{$lang.stus}</th>
    <th style="width: 20%;">{$lang.av}</th>
    <th style="width: 20%;">{$lang.comm}</th>
    <th style="width: 20%;">{$lang.update}</th>
	<th style="width: 2%;"></th>
</tr>
{foreach from=$_domains item=item name=domains}
{if $smarty.foreach.domains.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}">
    <td>{$item->host}</td>
	<td>{if $item->status eq '1'}{$lang.std}{elseif $item->status eq '2'}{$lang.szpt}{else}{$lang.snpr}{/if}</td>
    <td><a href="#null" onclick="get_window('/autosys/domains.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc}</a></td>
    <th style="cursor:pointer; {if $item->comment|strpos:"!" ne 0}color:#F00{/if}" onclick="return false;" id="cg_{$item->id}" ondblclick="ecd(this, 'groups');">{$item->comment}</th>
    <td>{$item->up_date}</td>    
	<td><a href="#" onclick="delete_domain('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
</table>
</form>