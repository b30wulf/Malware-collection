<form action="/admins/cmd_import.html?id={$admin->id}&window=1" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">
<div style="max-height: 600px; overflow: auto">
<br />
<table cellspacing="1" cellpadding="5" style="width:100%; font-size:10px; text-align: center;">
<tr class="bg3">
    <td style="width:50px;">ID</td>
    <td>Название фильтра</td>
    <td style="width:200px;">&nbsp;</td>
</tr>
{foreach from=$list item=item name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onMouseMove="this.className = 'bg4'" onMouseOut="this.className = 'bg{$bg}'">
    <th>{$item->id}</th>
    <th style="font-size:9px">{$item->name}</th>
    <th>
    {assign var="status" value="cache/fdi/`$admin->id`_`$item->id`"} 
    <select name="fdi{$item->id}" style="width:100%">
    <option value="on"{if $status|file_exists eq true} selected="selected"{/if}>Не импортировать данные</option>
    <option value="off"{if $status|file_exists ne true} selected="selected"{/if}>Импортировать данные</option>
    </select>
    </th>
  </tr>
{/foreach}
</table>
<br />
<input type="button" name="submit" style="width:100%" value="Сохранить" onclick="submit_{$rand_name}();" />
</div>
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Настройки импорта  - {$admin->link}';

function submit_{$rand_name}(){ldelim}
hax(document.forms['add_sub{$rand_name}'].action,
	{ldelim}
		method: document.forms['add_sub{$rand_name}'].method,
		form: 'add_sub{$rand_name}',
		id: id_{$rand_name} + '_content',
		nohistory:true,
		nocache:true,
		destroy:true,
		rc:true
	{rdelim}
)
document.getElementById(id_{$rand_name} + '_content').innerHTML = '<br /><div align="center"><img src="/images/indicator.gif" title="" /></div>';
{rdelim};
</script> 