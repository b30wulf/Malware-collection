{if $Cur.ajax ne 1}
<div class="top_menu">
<div class="top_menu_left"><a href="/catalog/add_sub.html" onclick="return get_window('/catalog/add_sub.html?window=1');">Добавить раздел</a>&nbsp;<a href="/catalog/add_filter.html" onclick="return get_window('/catalog/add_filter.html?window=1');">Добавить фильтр</a>&nbsp;</div>
</div>
<hr />
<div id="cats_content">
{/if}
{strip}
<ul id="cats" class="filetree">
<li style="font-weight:bold">Список  фильтров</li>
<li><span class="file" id="c_unnecessary" style="cursor: pointer" onclick="get_window('/logs/unnecessary.html?window=1', {ldelim}name: 'unnecessary'{rdelim});">Не использованные домены</span></li>
{foreach from=$catalog item=cat1 name=cat1}
{if $cat1->host ne ''}
	<li><span class="file" id="c_{$cat1->id}">{$cat1->name} ({$cat1->host}) <a href="/catalog/cut_filter-{$cat1->id}.html" onclick="return get_window('/catalog/cut_filter-{$cat1->id}.html?window=1', {ldelim}name:'cut_{$cat1->id}',height: 500{rdelim});"><img src="/images/icons/cut.gif"  title="Переместить фильтр" /></a> <a href="/catalog/edit_filter-{$cat1->id}.html" onclick="return get_window('/catalog/edit_filter-{$cat1->id}.html?window=1', {ldelim}name:'edit_{$cat1->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить фильтр" /></a> <a href="/catalog/remove-{$cat1->id}.html" onclick="return get_window('/catalog/remove-{$cat1->id}.html?window=1', {ldelim}name:'delete_{$cat1->id}'{rdelim});"><img src="/images/icons/page_delete.gif" title="Удалить фильтр" /></a></span></li>
{else}
	<li><span class="folder" id="c_{$cat1->id}">{$cat1->name} <a href="/catalog/add_sub-{$cat1->id}.html" onclick="return get_window('/catalog/add_sub-{$cat1->id}.html?window=1');"><img src="/images/icons/folder_new.gif" title="Добавить подраздел" /></a> <a href="/catalog/add_filter-{$cat1->id}.html" onclick="return get_window('/catalog/add_filter-{$cat1->id}.html?window=1');"><img src="/images/icons/action_paste.gif" title="Добавить фильтр" /></a> <a href="/catalog/edit_sub-{$cat1->id}.html" onclick="return get_window('/catalog/edit_sub-{$cat1->id}.html?window=1', {ldelim}name:'edit_{$cat1->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить раздел" /></a> <a href="/catalog/remove-{$cat1->id}.html" onclick="return get_window('/catalog/remove-{$cat1->id}.html?window=1', {ldelim}name:'delete_{$cat1->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" title="Удалить раздел" /></a></span>
	{foreach from=$cat1->sub item=cat2 name=cat2}
    {if $smarty.foreach.cat2.first}<ul>{/if}
    {if $cat2->host ne ''}
    	<li><span class="file" id="c_{$cat2->id}">{$cat2->name} ({$cat2->host}) <a href="/catalog/cut_filter-{$cat2->id}.html" onclick="return get_window('/catalog/cut_filter-{$cat2->id}.html?window=1', {ldelim}name:'cut_{$cat2->id}',height: 500{rdelim});"><img src="/images/icons/cut.gif"  title="Переместить фильтр" /></a> <a href="/catalog/edit_filter-{$cat2->id}.html" onclick="return get_window('/catalog/edit_filter-{$cat2->id}.html?window=1', {ldelim}name:'edit_{$cat2->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить фильтр" /></a> <a href="/catalog/remove-{$cat2->id}.html" onclick="return get_window('/catalog/remove-{$cat2->id}.html?window=1', {ldelim}name:'delete_{$cat2->id}'{rdelim});"><img src="/images/icons/page_delete.gif" title="Удалить фильтр" /></a></span></li>
    {else}
    	<li><span class="folder" id="c_{$cat2->id}">{$cat2->name} <a href="/catalog/add_sub-{$cat2->id}.html" onclick="return get_window('/catalog/add_sub-{$cat2->id}.html?window=1');"><img src="/images/icons/folder_new.gif" title="Добавить подраздел" /></a> <a href="/catalog/add_filter-{$cat2->id}.html" onclick="return get_window('/catalog/add_filter-{$cat2->id}.html?window=1');"><img src="/images/icons/action_paste.gif" title="Добавить фильтр" /></a> <a href="/catalog/edit_sub-{$cat2->id}.html" onclick="return get_window('/catalog/edit_sub-{$cat2->id}.html?window=1', {ldelim}name:'edit_{$cat2->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить раздел" /></a> <a href="/catalog/remove-{$cat2->id}.html" onclick="return get_window('/catalog/remove-{$cat2->id}.html?window=1', {ldelim}name:'delete_{$cat2->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" title="Удалить раздел" /></a></span>
        {foreach from=$cat2->sub item=cat3 name=cat3}
        {if $smarty.foreach.cat3.first}<ul>{/if}
        {if $cat3->host ne ''}
        	<li><span class="file" id="c_{$cat3->id}">{$cat3->name} ({$cat3->host}) <a href="/catalog/cut_filter-{$cat3->id}.html" onclick="return get_window('/catalog/cut_filter-{$cat3->id}.html?window=1', {ldelim}name:'cut_{$cat3->id}',height: 500{rdelim});"><img src="/images/icons/cut.gif"  title="Переместить фильтр" /></a> <a href="/catalog/edit_filter-{$cat3->id}.html" onclick="return get_window('/catalog/edit_filter-{$cat3->id}.html?window=1', {ldelim}name:'edit_{$cat3->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить фильтр" /></a> <a href="/catalog/remove-{$cat3->id}.html" onclick="return get_window('/catalog/remove-{$cat3->id}.html?window=1', {ldelim}name:'delete_{$cat3->id}'{rdelim});"><img src="/images/icons/page_delete.gif" title="Удалить фильтр" /></a></span></li>
        {else}
        	<li><span class="folder" id="c_{$cat3->id}">{$cat3->name} <a href="/catalog/add_filter-{$cat3->id}.html" onclick="return get_window('/catalog/add_filter-{$cat3->id}.html?window=1');"><img src="/images/icons/action_paste.gif" title="Добавить фильтр" /></a> <a href="/catalog/edit_sub-{$cat3->id}.html" onclick="return get_window('/catalog/edit_sub-{$cat3->id}.html?window=1', {ldelim}name:'edit_{$cat3->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить раздел" /></a> <a href="/catalog/remove-{$cat3->id}.html" onclick="return get_window('/catalog/remove-{$cat3->id}.html?window=1', {ldelim}name:'delete_{$cat3->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" title="Удалить раздел" /></a></span>
            {foreach from=$cat3->sub item=cat4 name=cat4}
            {if $smarty.foreach.cat4.first}<ul>{/if}
            <li><span class="file" id="c_{$cat4->id}">{$cat4->name} ({$cat4->host}) <a href="/catalog/cut_filter-{$cat4->id}.html" onclick="return get_window('/catalog/cut_filter-{$cat4->id}.html?window=1', {ldelim}name:'cut_{$cat4->id}',height: 500{rdelim});"><img src="/images/icons/cut.gif"  title="Переместить фильтр" /></a> <a href="/catalog/edit_filter-{$cat4->id}.html" onclick="return get_window('/catalog/edit_filter-{$cat4->id}.html?window=1', {ldelim}name:'edit_{$cat4->id}'{rdelim});"><img src="/images/icons/page_edit.gif" title="Изменить фильтр" /></a> <a href="/catalog/remove-{$cat4->id}.html" onclick="return get_window('/catalog/remove-{$cat4->id}.html?window=1', {ldelim}name:'delete_{$cat4->id}'{rdelim});"><img src="/images/icons/page_delete.gif" title="Удалить фильтр" /></a></span></li>
            {if $smarty.foreach.cat4.last}</ul>{/if}
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
</ul>
{/strip}
{if $Cur.ajax ne 1}</div>{/if}