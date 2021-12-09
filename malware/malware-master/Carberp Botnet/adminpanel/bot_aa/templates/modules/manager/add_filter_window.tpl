<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Добавления ссылок{if $parent}<span style="font-size:10px;"> (в раздел {$parent->name})</span>{/if}';

var all_filter_item_{$rand_name} = 0;
function create_data_filter_{$rand_name}(z, add_html){ldelim}
	if (all_filter_item_{$rand_name} != 0){ldelim}
		if(confirm('Все текущие данные, заполненные в полях исчезнут! Продолжить?') == false){ldelim}
			document.forms['add_filter{$rand_name}'].elements['fields'].selectedIndex = i-1;
			return;
		{rdelim}
	{rdelim}
	all_filter_item_{$rand_name} = z;
	create_data_filter(z, add_html);
{rdelim}

function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['add_filter{$rand_name}'].action,
		method: document.forms['add_filter{$rand_name}'].method,
		form: 'add_filter{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim}

</script>

{$javascript_end}
{if $save eq ''}
<form action="{if $parent}/manager/add_filter-{$Cur.id}.html?window=1{else}/manager/add_filter.html?window=1{/if}" name="add_filter{$rand_name}" id="add_filter{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="return false;">
{if $errors ne ""}
<div align="center" style="padding-top: 10px; padding-bottom: 10px">
{$errors}
</div>
{/if}
<div align="center" style="padding-left: 16px; width: 999px; height: 400px;">
<table cellspacing="1" cellpadding="0" style="width: 100%; height:100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th style="text-align: left; width: 100px; height: 20px;">Название (коментарий):</th>
    <th style="text-align: left; width: 250px; height: 20px;"><input name="name" type="text" value="{$smarty.post.name}" class="user" maxlength="32" /></th>
</tr>
<tr class="bgp2">
    <th colspan="2" style="text-align: left; width: 250px; height: 20px;">Ссылки:</th>
</tr>
<tr class="bg1">
  <th colspan="2" style="text-align: left; width: 250px; height: 20px; font-size: 10px">
  <ul style="list-style: decimal">
  <li>Каждая строка отдельный хост/домен/сайт</li>
  <li>Субдомены не нужно сюда писать, т.к. должен быть только домен верхнего уровня!</li>
  <li>Рекомендуемое максимальное число строк/хостов/доменов/сайтов: 25</li>
  <li>Добавляються только те хосты/домены/сайты у которых нету фильтров, и которых нету в менеджере ссылок</li>
  <li>хост/домен/сайт обязательно с http://</li>
  <li>http:// и https:// не имеет значения, т.к. береться только домен/хост верхнего уровня, протокол не учитываеться!</li>
  </ul>
  </th>
</tr>
<tr class="bgp2">
  <th colspan="2" style="text-align: left; width: 250px; height: 100%; padding:0px; margin:0px;"><textarea name="links" cols="1" rows="1" style="width:100%; height: 100%;">{$smarty.post.links}</textarea></th>
</tr>
<tr class="bgp1">
  <th colspan="2" style="height: 20px;"><input name="submit" type="button" value="Добавить" style="width: 100%" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
</div>
</form>
<script language="javascript" type="application/javascript">
create_data_filter_{$rand_name}('{$smarty.post.fields}', 'add_html{$rand_name}');

var {$value_name}=new Array();
{foreach from=$smarty.post.p item=item key=key}
{$value_name}['{$key}'] = new Array();
{foreach from=$item item=i key=k}
{if $key eq 'formgrabber'}
{$value_name}['{$key}']['{$k}'] = new Array();
{foreach from=$i item=ie key=ke}
{$value_name}['{$key}']['{$k}']['{$ke}'] = '{$ie}';
{/foreach}
{else}
{$value_name}['{$key}']['{$k}'] = '{$i}';
{/if}
{/foreach}
{/foreach}
set_data_filter({$value_name}, null, 'add_filter{$rand_name}');
</script>
{else}
<center>
  <h2>Новые ссылки добавлены!</h2></center>
{if $parent}
<center>
  <span style="font-size:14px;">Ссылки добавлены в {$parent->name}.</span>
</center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(id_{$rand_name} + '_wid', 1);">Закрыть окно</a></center>
<script language="javascript" type="application/javascript">
{literal}
hax('?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
{/literal}
</script>
{/if}