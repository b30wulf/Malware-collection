<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">
<br />
<div align="center">
Добавить систему: <input name="sname" style="width:200px" />&nbsp;<select name="stype" style="width:200px"><option value="SH">Шейвер включен</option><option value="OFF">Получение отключено</option><option value="TRUE">Получение включено</option></select>&nbsp;<input type="button" value="Добавить" onclick="return add_sh('{$admin->id}', document.forms['add_sub{$rand_name}'].elements['sname'].value,document.forms['add_sub{$rand_name}'].elements['stype'].value,document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));" />
</div>
<br />
<div align="center">
<a href="/admins/cmd_shever.html?window=1&id={$admin->id}&str=cfile" target="_blank">Скачать текущий ({$files[0]|size_format})</a> / <a href="/admins/cmd_shever.html?id={$admin->id}&str=pfile" target="_blank">Скачать предыдуший ({$files[1]|size_format})</a>
</div>
<br />
<div align="center">{if $stat eq 'OK!'}<a href="#" onclick="return stat_sh('{$admin->id}', document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));">Выключить функцию контроля приема</a>{else}<a href="#" onclick="return stat_sh('{$admin->id}', document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));">Включить функцию контроля приема</a>{/if}</div>
<br />
<table cellspacing="1" cellpadding="5" style="width:100%; font-size:10px; text-align: center;">
<tr class="bg3">
    <td style="width:80%">Система</td>
    <td style="width:20%;">Действие</td>
    <td style="width:10px;">&nbsp;</td>
</tr>
{foreach from=$list item=item name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onMouseMove="this.className = 'bg4'" onMouseOut="this.className = 'bg{$bg}'">
    <th style="font-size:9px">{$item[0]}</th>
    <th style="font-size:9px">{if $item[1] eq 'SH'}Шейвер включен{elseif $item[1] eq 'OFF'}Получение отключено{else}{$item[1]}{/if} </th>
    <th><a href="#" onclick="return delete_sh('{$admin->id}', '{$item[0]}',document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));"><img src="/images/delete.png" alt="Удалить" title="Удалить" border="0"></a></th>
  </tr>
{/foreach}
</table>

</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Шейвер кабов  - {$admin->link}';

</script>