{if $Cur.ajax ne 1}
<div class="top_menu">
<div class="top_menu_left"><a href="/manager/add_sub.html" onclick="return get_window('/manager/add_sub.html?window=1');">Добавить раздел</a>&nbsp;<a href="/manager/add_filter.html" onclick="return get_window('/manager/add_filter.html?window=1');">Добавить ссылки</a>&nbsp;</div>
</div>
<hr />
<div id="cats_content">
{/if}
<ul id="cats" class="filetree">
<li style="font-weight:bold">Список  ссылок</li>
{foreach from=$catalog item=cat1 name=cat1}
{if $cat1->host ne ''}
	<li><span class="file" id="c_{$cat1->id}" title="Дата создания: {$cat1->post_date}">{$cat1->host} ({$cat1->name}) <a href="/manager/edit_filter-{$cat1->id}.html" onclick="return get_window('/logs/unnecessary-{$cat1->id}.html?window=1', {ldelim}name:'comp_{$cat1->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/remove-{$cat1->id}.html" onclick="return get_window('/manager/remove-{$cat1->id}.html?window=1', {ldelim}name:'delete_{$cat1->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="Удалить фильтр" title="Удалить фильтр" /></a></span></li>
{else}
	<li><span class="folder" id="c_{$cat1->id}" title="Дата создания: {$cat1->post_date}">{$cat1->name} <a href="/manager/edit_filter-{$cat1->id}.html" onclick="return get_window('/logs/unnecessary-{$cat1->id}.html?window=1', {ldelim}name:'comp_{$cat1->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/add_sub-{$cat1->id}.html" onclick="return get_window('/manager/add_sub-{$cat1->id}.html?window=1');"><img src="/images/icons/folder_new.gif" alt="Добавить подраздел" title="Добавить подраздел" /></a> <a href="/manager/add_filter-{$cat1->id}.html" onclick="return get_window('/manager/add_filter-{$cat1->id}.html?window=1');"><img src="/images/icons/action_paste.gif" alt="Добавить ссылки" title="Добавить ссылки" /></a> <a href="/manager/edit_sub-{$cat1->id}.html" onclick="return get_window('/manager/edit_sub-{$cat1->id}.html?window=1', {ldelim}name:'edit_{$cat1->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="Изменить раздел" title="Изменить раздел" /></a> <a href="/manager/remove-{$cat1->id}.html" onclick="return get_window('/manager/remove-{$cat1->id}.html?window=1', {ldelim}name:'delete_{$cat1->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" alt="Удалить раздел" title="Удалить раздел" /></a></span>
	{foreach from=$cat1->sub item=cat2 name=cat2}
    {if $smarty.foreach.cat2.first}<ul>{/if}
    {if $cat2->host ne ''}
    	<li><span class="file" id="c_{$cat2->id}" title="Дата создания: {$cat2->post_date}">{$cat2->host} ({$cat2->name}) <a href="/manager/edit_filter-{$cat2->id}.html" onclick="return get_window('/logs/unnecessary-{$cat2->id}.html?window=1', {ldelim}name:'comp_{$cat2->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/remove-{$cat2->id}.html" onclick="return get_window('/manager/remove-{$cat2->id}.html?window=1', {ldelim}name:'delete_{$cat2->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="Удалить фильтр" title="Удалить фильтр" /></a></span></li>
    {else}
    	<li><span class="folder" id="c_{$cat2->id}" title="Дата создания: {$cat2->post_date}">{$cat2->name} <a href="/manager/edit_filter-{$cat2->id}.html" onclick="return get_window('/logs/unnecessary-{$cat2->id}.html?window=1', {ldelim}name:'comp_{$cat2->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/add_sub-{$cat2->id}.html" onclick="return get_window('/manager/add_sub-{$cat2->id}.html?window=1');"><img src="/images/icons/folder_new.gif" alt="Добавить подраздел" title="Добавить подраздел" /></a> <a href="/manager/add_filter-{$cat2->id}.html" onclick="return get_window('/manager/add_filter-{$cat2->id}.html?window=1');"><img src="/images/icons/action_paste.gif" alt="Добавить ссылки" title="Добавить ссылки" /></a> <a href="/manager/edit_sub-{$cat2->id}.html" onclick="return get_window('/manager/edit_sub-{$cat2->id}.html?window=1', {ldelim}name:'edit_{$cat2->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="Изменить раздел" title="Изменить раздел" /></a> <a href="/manager/remove-{$cat2->id}.html" onclick="return get_window('/manager/remove-{$cat2->id}.html?window=1', {ldelim}name:'delete_{$cat2->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" alt="Удалить раздел" title="Удалить раздел" /></a></span>
        {foreach from=$cat2->sub item=cat3 name=cat3}
        {if $smarty.foreach.cat3.first}<ul>{/if}
        {if $cat3->host ne ''}
        	<li><span class="file" id="c_{$cat3->id}" title="Дата создания: {$cat3->post_date}">{$cat3->host} ({$cat3->name}) <a href="/manager/edit_filter-{$cat3->id}.html" onclick="return get_window('/logs/unnecessary-{$cat3->id}.html?window=1', {ldelim}name:'comp_{$cat3->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/remove-{$cat3->id}.html" onclick="return get_window('/manager/remove-{$cat3->id}.html?window=1', {ldelim}name:'delete_{$cat3->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="Удалить фильтр" title="Удалить фильтр" /></a></span></li>
        {else}
        	<li><span class="folder" id="c_{$cat3->id}" title="Дата создания: {$cat3->post_date}">{$cat3->name} <a href="/manager/edit_filter-{$cat3->id}.html" onclick="return get_window('/logs/unnecessary-{$cat3->id}.html?window=1', {ldelim}name:'comp_{$cat3->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/add_filter-{$cat3->id}.html" onclick="return get_window('/manager/add_filter-{$cat3->id}.html?window=1');"><img src="/images/icons/action_paste.gif" alt="Добавить ссылки" title="Добавить ссылки" /></a> <a href="/manager/edit_sub-{$cat3->id}.html" onclick="return get_window('/manager/edit_sub-{$cat3->id}.html?window=1', {ldelim}name:'edit_{$cat3->id}'{rdelim});"><img src="/images/icons/page_edit.gif" alt="Изменить раздел" title="Изменить раздел" /></a> <a href="/manager/remove-{$cat3->id}.html" onclick="return get_window('/manager/remove-{$cat3->id}.html?window=1', {ldelim}name:'delete_{$cat3->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" alt="Удалить раздел" title="Удалить раздел" /></a></span>
            {foreach from=$cat3->sub item=cat4 name=cat4}
            {if $smarty.foreach.cat4.first}<ul>{/if}
            <li><span class="file" id="c_{$cat4->id}" title="Дата создания: {$cat4->post_date}">{$cat4->host} ({$cat4->name}) <a href="/manager/edit_filter-{$cat4->id}.html" onclick="return get_window('/logs/unnecessary-{$cat4->id}.html?window=1', {ldelim}name:'comp_{$cat4->id}'{rdelim});"><img src="/images/icons/icon_component.gif" alt="Посмотреть" title="Посмотреть" /></a> <a href="/manager/remove-{$cat4->id}.html" onclick="return get_window('/manager/remove-{$cat4->id}.html?window=1', {ldelim}name:'delete_{$cat4->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="Удалить фильтр" title="Удалить фильтр" /></a></span></li>
            {if $smarty.foreach.cat3.last}</ul>{/if}
            {/foreach}
            </li>
        {/if}
        {if $smarty.foreach.cat3.last}</ul>{/if}
        {/foreach}
        </li>
    {/if}
    {if $smarty.foreach.cat2.last}</ul>{/if}
    {/foreach}
	</li>
{/if}
{/foreach}
<li><span class="file" id="c_unnecessary" style="cursor: pointer" onclick="get_window('/logs/unnecessary.html?window=1', {ldelim}name: 'unnecessary'{rdelim});">Не существующих фильтров</span></li>
</ul>
{if $Cur.ajax ne 1}</div>{/if}