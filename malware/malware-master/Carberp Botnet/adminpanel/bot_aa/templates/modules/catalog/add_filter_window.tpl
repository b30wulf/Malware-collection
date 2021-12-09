<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Добавления фильтра{if $parent}<span style="font-size:10px;"> (в раздел {$parent->name})</span>{/if}';

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
<form action="{if $parent}/catalog/add_filter-{$Cur.id}.html?window=1{else}/catalog/add_filter.html?window=1{/if}" name="add_filter{$rand_name}" id="add_filter{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="return false;">
{if $errors ne ""}
<div align="center" style="padding-top: 10px; padding-bottom: 10px">
{$errors}
</div>
{/if}
<div align="center" style="padding-left: 16px; width: 999px; height: 500px;">
<table cellspacing="1" cellpadding="0" style="width: 100%; height:100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px; height: 20px;">Название</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2" style="height: 20px;">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px; height: 20px;">Сайт (домен)</th>
    <th style="text-align: left;"><input name="host" type="text" value="{$smarty.post.host}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2" style="height: 20px;">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px; height: 20px;">Сохранять логи с импорта в файл</th>
    <th style="text-align: left;"><input name="savelog" type="checkbox"{if $smarty.post.savelog eq 1} checked="checked"{/if} /></th>
</tr>
<tr class="bgp1">
  <th colspan="2" style="height: 20px;">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px; height: 20px;">Количество полей</th>
    <th style="text-align: left;">
    <select name="fields" class="user" onchange="create_data_filter_{$rand_name}(this.value, 'add_html{$rand_name}');">
    <option value="0">Выберите количество</option>
    <option value="1"{if $smarty.post.fields eq 1} selected="selected"{/if}>1 поле</option>
    <option value="2"{if $smarty.post.fields eq 2} selected="selected"{/if}>2 поля</option>
    <option value="3"{if $smarty.post.fields eq 3} selected="selected"{/if}>3 поля</option>
    <option value="4"{if $smarty.post.fields eq 4} selected="selected"{/if}>4 поля</option>
    <option value="5"{if $smarty.post.fields eq 5} selected="selected"{/if}>5 полей</option>
    <option value="6"{if $smarty.post.fields eq 6} selected="selected"{/if}>6 полей</option>
    <option value="7"{if $smarty.post.fields eq 7} selected="selected"{/if}>7 полей</option>
    <option value="8"{if $smarty.post.fields eq 8} selected="selected"{/if}>8 полей</option>
    <option value="9"{if $smarty.post.fields eq 9} selected="selected"{/if}>9 полей</option>
    <option value="10"{if $smarty.post.fields eq 10} selected="selected"{/if}>10 полей</option>
    </select>
    </th>
</tr>
<tr class="bgp1">
  <th height="25" colspan="2" style="height: 20px;">&nbsp;</th>
</tr>
<tr class="bgp2">
  <th colspan="2" id="add_html{$rand_name}"><center>Параметров фильтра еще нет, сделайте выбор колличества полей!</center></th>
</tr>
<tr class="bgp1">
  <th colspan="2" style="height: 20px;">&nbsp;</th>
</tr>
<tr class="bgp2">
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
  <h2>Новый фильтр добавлен!</h2></center>
{if $parent}
<center>
  <span style="font-size:14px;">Фильтр добавлен в {$parent->name}.</span>
</center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(id_{$rand_name} + '_wid', 1);">Закрыть окно</a></center>
<script language="javascript" type="application/javascript">
{literal}
document.location = '/catalog/';
/*
hax('/catalog/?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
*/
{/literal}
</script>
{/if}