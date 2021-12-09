<div id="div_sub_{$rand_name}"></div>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>

<table cellspacing="1" cellpadding="0" style="width: 700px; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="width: 600px; text-align: center">Дата добавления</td>
    <td style="text-align: center; width: 150px;">Размер</td>
    <td style="text-align: center"></td>
    <td style="text-align: center"></td>
    <td style="text-align: center"></td>
</tr>
{foreach from=$files item=f name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="font-size: 10px;">
	<th>{$f}</th>
    <th>{$f|sf}</th>
    <th><a href="/logs/admins_download.html?str={$f}&x=count_domain" target="_blank">домены</a>
    <th><a href="/logs/admins_download.html?str={$f}" target="_blank">просмотр</a></th>
    <th><a href="/logs/admins_download.html?str={$f}&type=1" target="_blank">скачать</a></th>
</tr>
{/foreach}
</table>
<br />

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
