
{if $threads|count > 0}
<hr />
<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg4" style="border: 1px solid #FFFFFF; height: 25px; font-size:13px">
	<th style="width:120px;">Файл</th>
    <th style="width:200px;">Обработано</th>
    <th style="width:150px;">Обрабатывается</th>
    <th style="width:200px;">Осталось</th>
    <th style="width:200px;">С ошибкой</th>
</tr>
{foreach from=$threads item=t key=k}
{if $smarty.foreach.clients.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; font-size:11px;">
	<th>{$k}</th>
    <th>{$t->count.obra} из {$t->count.all} ({$t->count.allp}%)</th>
    <th>{$t->count.2}</th>
    <th>{$t->count.ost} ({$t->count.ostp}%)</th>
    <th>{$t->count.3} ({$t->count.errp}%)</th>
</tr>
{/foreach}
</table>
<hr />
{else}
<hr />
<div align="center" style="font-size:18px; font-weight:bold">В данный момент скрипты не запущены</div>
<hr />
{/if}