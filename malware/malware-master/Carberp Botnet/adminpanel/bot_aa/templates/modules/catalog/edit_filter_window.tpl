<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Изменение фильтра {if $parent}<span style="font-size:10px;">(родитель:{$parent->name})</span>{/if}';

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
{if $save eq ''}
<form action="/catalog/edit_filter-{$Cur.id}.html?window=1" name="add_filter{$rand_name}" id="add_filter{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
<input name="fields" type="hidden" value="{$smarty.post.fields}" />
{if $errors ne ""}
<div align="center" style="padding-top: 10px; padding-bottom: 10px">{$errors}</div>
{/if}
<div style="width: 900px; padding-left: 16px">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Название</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Сайт (домен)</th>
    <th style="text-align: left;"><input name="host" type="text" value="{$smarty.post.host}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Сохранять логи с импорта в файл</th>
    <th style="text-align: left;"><input name="savelog" type="checkbox"{if $smarty.post.savelog eq 1} checked="checked"{/if} /></th>
</tr>
<tr class="bgp1">
  <th height="25" colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
  <th colspan="2" id="add_html{$rand_name}"><center>Параметров фильтра еще нет, сделайте выбор колличества полей!</center></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="Добавить" class="user" onclick="submit_{$rand_name}();" /></th>
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
<center><h2>Фильтр изменен!</h2></center>
{if $parent}
<center><span style="font-size:14px;">Фильтр изменен!<br />(Родитель: {$parent->name}).</span></center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">Закрыть окно</a></center>
<script language="javascript" type="application/javascript">
{literal}
document.location = '/catalog/';
/*
hax('/catalog/?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
*/
{/literal}
</script>
{/if}