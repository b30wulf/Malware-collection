<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.autosys.domains eq on}<a href="/autosys/domains.html">{$lang.domains}</a>&nbsp;{/if}
{if $_SESSION.user->access.autosys.builds eq on}<a id="current" href="/autosys/builds.html">{$lang.builds}</a>&nbsp;{/if}
{if $check_pid eq false}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/autosys/builds.html?str=start_builds">{$lang.start_builds}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">
<a href="/autosys/builds_add.html">{$lang.add_builds}</a>
</div>

</div>

<hr /><h2 style="text-align:center">{$lang.l1}</h2><hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 1%;">#</th>
	<th style="width: 10%;"></th>
    <th style="width: 10%;"></th>
	<th style="width: 25%;">{$lang.stus}</th>
    <th style="width: 25%;">AV / PRIO</th>
    <th style="width: 30%;">{$lang.update}</th>
	<th style="width: 1%;"></th>
    <th style="width: 1%;"></th>
</tr>
{if $check_pid eq false}
{foreach from=$builds.1 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>{if $item->file_orig ne ''}<a href="/cfg/{$item->file_orig}">{$lang.orig}</a>{else}-{/if}</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td><a href="/autosys/builds_edit-{$item->id}.html"><img src="/images/edit.png" alt="{$lang.edit}" /></a></td>
	<td><a href="#null" onclick="delete_build('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
{else}
{foreach from=$builds.1 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>{if $item->file_orig ne ''}<a href="/cfg/{$item->file_orig}">{$lang.orig}</a>{else}-{/if}</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
{/foreach}
{/if}

{if $check_pid eq false}
{foreach from=$builds.2 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>-</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td><a href="/autosys/builds_edit-{$item->id}.html"><img src="/images/edit.png" alt="{$lang.edit}" /></a></td>
	<td><a href="#null" onclick="delete_build('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
{else}
{foreach from=$builds.2 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>-</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
{/foreach}
{/if}
</table>

<hr /><h2 style="text-align:center">{$lang.l2}</h2><hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 1%;">#</th>
	<th style="width: 10%;"></th>
    <th style="width: 10%;"></th>
	<th style="width: 25%;">{$lang.stus}</th>
    <th style="width: 25%;">AV / PRIO</th>
    <th style="width: 30%;">{$lang.update}</th>
	<th style="width: 1%;"></th>
    <th style="width: 1%;"></th>
</tr>
{if $check_pid eq false}
{foreach from=$builds.3 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>-</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td><a href="/autosys/builds_edit-{$item->id}.html"><img src="/images/edit.png" alt="{$lang.edit}" /></a></td> 
	<td><a href="#null" onclick="delete_build('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
{else}
{foreach from=$builds.3 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>-</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td>&nbsp;</td> 
	<td>&nbsp;</td>
</tr>
{/foreach}
{/if}
</table>
{*
<hr /><h2 style="text-align:center">{$lang.l3}</h2><hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 1%;">#</th>
	<th style="width: 10%;"></th>
    <th style="width: 10%;"></th>
	<th style="width: 25%;">{$lang.stus}</th>
    <th style="width: 25%;">AV / PRIO</th>
    <th style="width: 30%;">{$lang.update}</th>
	<th style="width: 1%;"></th>
    <th style="width: 1%;"></th>
</tr>
{if $check_pid eq false}
{foreach from=$builds.4 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>{if $item->file_orig ne ''}<a href="/cache/originals/{$item->file_orig}">{$lang.orig}</a>{else}-{/if}</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td><a href="/autosys/builds_edit-{$item->id}.html"><img src="/images/edit.png" alt="{$lang.edit}" /></a></td>
	<td><a href="#null" onclick="delete_build('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
{foreach from=$builds.5 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>-</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td><a href="/autosys/builds_edit-{$item->id}.html"><img src="/images/edit.png" alt="{$lang.edit}" /></a></td>
	<td><a href="#null" onclick="delete_build('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
{else}
{foreach from=$builds.4 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>{if $item->file_orig ne ''}<a href="/cache/originals/{$item->file_orig}">{$lang.orig}</a>{else}-{/if}</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
{/foreach}
{foreach from=$builds.5 item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}" id="bt{$item->id}">
	<td>{$item->id}</td>
    <td>-</td>
    <td>{if $item->file_crypt ne ''}<a href="/cfg/{$item->file_crypt}">{$lang.crypt}</a>{else}-{/if}</td>
	<td>{$item->status|get_status}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc} / {$item->prio}</a></td>
    <td>{$item->up_date}</td>  
    <td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
{/foreach}
{/if}
</table>
*}