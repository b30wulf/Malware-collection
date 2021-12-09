<form action="/admins/cmd_work_adm.html?window=1&amp;id={$admin->id}" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
<br />
<div align="center" style="font-size:14px">Текуший статус: {if $data eq 'true'}Включена{elseif $data eq 'false'}Выключена{else}Неизвестно{/if}</div>
<br /><br />
<select name="work" style="width: 100%;">
<option value="true"{if $data eq 'false'}selected="selected"{/if}>Включить</option>
<option value="false"{if $data eq 'true'}selected="selected"{/if}>Выключить</option>
</select>
<br /><br />
<input type="hidden" name="submit" value="1">
<input class=i4 type="button" name="submit" value="Готово" style="width:100%;" onclick="submit_{$rand_name}();">
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Работа админки  - {$admin->link}';

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
);
document.getElementById(id_{$rand_name} + '_content').innerHTML = '<br /><div align="center"><img src="/images/indicator.gif" title="Загрузка" alt="Загрузка" /></div>';
{rdelim}

</script>