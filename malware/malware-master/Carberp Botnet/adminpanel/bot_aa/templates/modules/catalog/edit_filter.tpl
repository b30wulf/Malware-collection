{if $save eq ''}
<form name="add_filter" action="" enctype="application/x-www-form-urlencoded" method="post">
<input name="fields" type="hidden" value="{$smarty.post.fields}" />
<h2>Изменение фильтра {if $parent}<span style="font-size:14px;">(Родитель: {$parent->name})</span>{/if}</h2>
{if $errors ne ""}
<div align="center">
{$errors}
</div>
<br />
{/if}
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
  <th height="25" colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
  <th colspan="2" id="add_html"><center>Параметров фильтра еще нет, сделайте выбор колличества полей!</center></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="submit" value="Добавить" style="width: 100%" /></th>
</tr>
</table>
</form>
<script language="javascript" type="application/javascript">
var all_filter_item = 0;
function create_data_filter_null(z, add_html){ldelim}
	if (all_filter_item != 0){ldelim}
		if(confirm('Все текущие данные, заполненные в полях исчезнут! Продолжить?') == false){ldelim}
			document.forms['add_filter'].elements['fields'].selectedIndex = i-1;
			return;
		{rdelim}
	{rdelim}
	all_filter_item = z;
	create_data_filter(z, add_html);
{rdelim}
create_data_filter('{$smarty.post.fields}', 'add_html');
var p=new Array();
{foreach from=$smarty.post.p item=item key=key}
p['{$key}'] = new Array();
{foreach from=$item item=i key=k}
{if $key eq 'formgrabber'}
p['{$key}']['{$k}'] = new Array();
{foreach from=$i item=ie key=ke}
p['{$key}']['{$k}']['{$ke}'] = '{$ie}';
{/foreach}
{else}
p['{$key}']['{$k}'] = '{$i}';
{/if}
{/foreach}
{/foreach}
set_data_filter(p, 'p', 'add_filter');
</script>
{else}
<center><h2>Фильтр изменен!</h2></center>
{if $parent}
<center><span style="font-size:14px;">Фильтр изменен! (Родитель: {$parent->name}).</span></center>
{/if}
<br />
<center><a href="/catalog/">Перейти в каталог фильтров</a></center>
{/if}