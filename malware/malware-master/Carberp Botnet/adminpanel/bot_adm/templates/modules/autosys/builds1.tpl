<div class="top_menu" style="height: 40px">
<div class="top_menu_left">
{if $_SESSION.user->access.autosys.domains eq on}<a href="/autosys/domains.html">{$lang.domains}</a>&nbsp;{/if}
{if $_SESSION.user->access.autosys.builds eq on}<a href="/autosys/builds.html" id="current">{$lang.builds}</a>&nbsp;{/if}
{if $_SESSION.user->access.autosys.builds_traf eq on}<a href="/autosys/builds_traf.html">{$lang.builds_traf}</a>&nbsp;{/if}
{if $check_pid eq false}{if $_SESSION.user->access.autosys.builds eq on}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/autosys/builds.html?str=start_builds">{$lang.start_builds}</a>&nbsp;{/if}{/if}
</div>

<div class="top_menu_right" style="top: -7px">
{if $_SESSION.user->access.autosys.builds_add eq on}
<form action="/autosys/builds.html" method="post" enctype="multipart/form-data">
{$lang.file}: <input type="file" id="file" name="file" />
<input type="submit" name="update" value="{$lang.add}" />
<div style="line-height: 10px;"><input id="recrypt" name="recrypt" type="checkbox" /> <label for="recrypt">{$lang.recrypt}</label></div>
</form>
{/if}
</div>

</div>
<hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 20%;">{$lang.file}</th>
	<th style="width: 20%;">{$lang.status}</th>
    <th style="width: 40%;">{$lang.av}</th>
    <th style="width: 20%;">{$lang.update}</th>
    <th style="width: 1%;"></th>
</tr>
{foreach from=$builds item=item name=builds}
{if $smarty.foreach.builds.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}">
    <td><a href="/cfg/{$item->file}#hash(md5:{$item->md5})">{$item->file}</a></td>
	<td>{if $item->status eq '1'}{$lang.std}{elseif $item->status eq '2'}{$lang.szpt}{else}{$lang.snpr}{/if}</td>
    <td><a href="#null" onclick="get_window('/autosys/builds.html?id={$item->id}&amp;str=view_av', {ldelim}name:'av{$item->id}', widht: 800{rdelim});">{$item->avcf} {$lang.iz} {$item->avc}</a></td>
    <td>{$item->up_date}</td>    
    <td><a href="#" onclick="delete_build('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
</table>
