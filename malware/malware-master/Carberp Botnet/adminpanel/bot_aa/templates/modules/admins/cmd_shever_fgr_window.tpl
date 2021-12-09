<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">
<br />
<div align="center">
Добавить домен: <input name="sname" style="width:200px" />&nbsp;<input type="button" value="Добавить" onclick="return add_sh_fgr('{$admin->id}', document.forms['add_sub{$rand_name}'].elements['sname'].value,document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));" />
</div>
<br /><br />
<div align="center">
Выполнить текст: <input name="dname" style="width:200px" />&nbsp;<input type="button" value="Сохранить" onclick="return add_sh_fgr_d('{$admin->id}', document.forms['add_sub{$rand_name}'].elements['dname'].value,document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));" />
</div>
<br />
<table cellspacing="1" cellpadding="5" style="width:100%; font-size:10px; text-align: center;">
<tr class="bg3">
    <td style="width:80%">Домен</td>
    <td style="width:10px;">&nbsp;</td>
</tr>
{foreach from=$list item=item name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onMouseMove="this.className = 'bg4'" onMouseOut="this.className = 'bg{$bg}'">
    <th style="font-size:9px">{$item}</th>
    <th><a href="#" onclick="return delete_sh_fgr('{$admin->id}', '{$item}',document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));"><img src="/images/delete.png" alt="Удалить" title="Удалить" border="0"></a></th>
  </tr>
{/foreach}
</table>

</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Шейвер кабов  - {$admin->link}';

</script>