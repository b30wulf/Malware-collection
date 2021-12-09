<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.bots.jobs_add eq on}<a href="#" onclick="get_window('/bots/domains_add.html?window=1', {ldelim}name:'domains_add', widht: 800{rdelim});">{$lang.domains_add}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">
<a href="/bots/jobs.html">{$lang.domains_nkz}</a>&nbsp;
</div>

</div>
<hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<th style="width: 20%;">{$lang.domain}</th>
	<th style="width: 20%;">{$lang.status}</th>
    <th style="width: 40%;" colspan="2">{$lang.av}</th>
    <th style="width: 20%;">{$lang.update}</th>
	<th style="width: 2%;"></th>
</tr>
{foreach from=$domains item=item name=domains}
{if $smarty.foreach.domains.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}">
    <td>{$item->host}</td>
	<td>{if $item->status eq '1'}{$lang.std}{elseif $item->status eq '2'}{$lang.szpt}{else}{$lang.snpr}{/if}</td>
    <td>{$item->avcf} {$lang.iz} {$item->avc}</td>
    <td style="width: 20%">{$item->av|to_br}</td>
    <td>{$item->up_date}</td>    
	<td><a href="#" onclick="delete_domain('{$item->id}');"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
</table>
