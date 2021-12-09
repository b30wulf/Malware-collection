<div class="top_menu">

<div class="top_menu_left">
<a href="/bots/jobs.html">{$lang.domains_nkz}</a>&nbsp;
</div>

<div class="top_menu_right">
<form action="/bots/links.html" method="post" enctype="multipart/form-data">
{$lang.clinks}: <input type="text" id="link" name="link" style="width: 300px" maxlength="128" />
<input type="submit" name="submit" value="{$lang.add}" />
</form>
</div>

</div>

<hr />

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<td style="width: 5%;">#</td>
	<td>{$lang.clinks}</td>
    <td style="width: 20%;">{$lang.dateadd}</td>
	<td style="width: 2%;"></td>
</tr>
{foreach from=$links item=link name=links}
{if $smarty.foreach.links.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}">
	<td>{$link->id}</td>
    <td>{$link->link}</td>
    <td>{$link->post_date}</td>
	<td><a href="/bots/links-{$link->id}.html"><img src="/images/delete.png" alt="{$lang.delet}" /></a></td>
</tr>
{/foreach}
</table>
