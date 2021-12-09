{if $Cur.ajax ne 1}
<div class="top_menu">
<div class="top_menu_left"><a href="/filters/add_sub.html" onclick="return get_window('/filters/add_sub.html?window=1');">{$lang.fdr1}</a>&nbsp;<a href="/filters/add_filter.html" onclick="return get_window('/filters/add_filter.html?window=1');">{$lang.fdf1}</a>&nbsp;</div>
</div>
<hr />
<div id="cats_content">
{/if}
<ul id="catse" class="filetree">
<li style="font-weight:bold">{$lang.fsf}</li>
<li><span class="file" id="c_unnecessary" onclick="get_window('/filters/unnecessary.html?window=1', {ldelim}name: 'unnecessary',height: 650{rdelim});">{$lang.fnid}</span></li>
{foreach from=$catalog item=cat1 name=cat1}
{if $cat1->host ne ''}
	<li><span class="file" id="c_{$cat1->id}" title="{$lang.fds}: {$cat1->post_date}">{$cat1->name} ({$cat1->host}) <a href="/filters/edit_filter-{$cat1->id}.html" onclick="return get_window('/filters/edit_filter-{$cat1->id}.html?window=1', {ldelim}name:'edit_{$cat1->id}'{rdelim});"><img src="/images/icons/page_edit.gif" alt="{$lang.fif1}" title="{$lang.fif1}" /></a> <a href="/filters/remove-{$cat1->id}.html" onclick="return get_window('/filters/remove-{$cat1->id}.html?window=1', {ldelim}name:'delete_{$cat1->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="{$lang.fyf1}" title="{$lang.fyf1}" /></a></span></li>
{else}
	<li><span class="folder" id="c_{$cat1->id}" title="{$lang.fds}: {$cat1->post_date}">{$cat1->name} <a href="/filters/add_sub-{$cat1->id}.html" onclick="return get_window('/filters/add_sub-{$cat1->id}.html?window=1');"><img src="/images/icons/folder_new.gif" alt="{$lang.fdp1}" title="{$lang.fdp1}" /></a> <a href="/filters/add_filter-{$cat1->id}.html" onclick="return get_window('/filters/add_filter-{$cat1->id}.html?window=1');"><img src="/images/icons/action_paste.gif" alt="{$lang.fdf1}" title="{$lang.fdf1}" /></a> <a href="/filters/edit_sub-{$cat1->id}.html" onclick="return get_window('/filters/edit_sub-{$cat1->id}.html?window=1', {ldelim}name:'edit_{$cat1->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="{$lang.fir1}" title="{$lang.fir1}" /></a> <a href="/filters/remove-{$cat1->id}.html" onclick="return get_window('/filters/remove-{$cat1->id}.html?window=1', {ldelim}name:'delete_{$cat1->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" alt="{$lang.fyr1}" title="{$lang.fyr1}" /></a></span>
	{foreach from=$cat1->sub item=cat2 name=cat2}
    {if $smarty.foreach.cat2.first}<ul>{/if}
    {if $cat2->host ne ''}
    	<li><span class="file" id="c_{$cat2->id}" title="{$lang.fds}: {$cat2->post_date}">{$cat2->name} ({$cat2->host}) <a href="/filters/edit_filter-{$cat2->id}.html" onclick="return get_window('/filters/edit_filter-{$cat2->id}.html?window=1', {ldelim}name:'edit_{$cat2->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="{$lang.fif1}" title="{$lang.fif1}" /></a> <a href="/filters/remove-{$cat2->id}.html" onclick="return get_window('/filters/remove-{$cat2->id}.html?window=1', {ldelim}name:'delete_{$cat2->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="{$lang.fyf1}" title="{$lang.fyf1}" /></a></span></li>
    {else}
    	<li><span class="folder" id="c_{$cat2->id}" title="{$lang.fds}: {$cat2->post_date}">{$cat2->name} <a href="/filters/add_sub-{$cat2->id}.html" onclick="return get_window('/filters/add_sub-{$cat2->id}.html?window=1');"><img src="/images/icons/folder_new.gif" alt="{$lang.fdp1}" title="{$lang.fdp1}" /></a> <a href="/filters/add_filter-{$cat2->id}.html" onclick="return get_window('/filters/add_filter-{$cat2->id}.html?window=1');"><img src="/images/icons/action_paste.gif" alt="{$lang.fdf1}" title="{$lang.fdf1}" /></a> <a href="/filters/edit_sub-{$cat2->id}.html" onclick="return get_window('/filters/edit_sub-{$cat2->id}.html?window=1', {ldelim}name:'edit_{$cat2->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="{$lang.fir1}" title="{$lang.fir1}" /></a> <a href="/filters/remove-{$cat2->id}.html" onclick="return get_window('/filters/remove-{$cat2->id}.html?window=1', {ldelim}name:'delete_{$cat2->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" alt="{$lang.fyr1}" title="{$lang.fyr1}" /></a></span>
        {foreach from=$cat2->sub item=cat3 name=cat3}
        {if $smarty.foreach.cat3.first}<ul>{/if}
        {if $cat3->host ne ''}
        	<li><span class="file" id="c_{$cat3->id}" title="{$lang.fds}: {$cat3->post_date}">{$cat3->name} ({$cat3->host}) <a href="/filters/edit_filter-{$cat3->id}.html" onclick="return get_window('/filters/edit_filter-{$cat3->id}.html?window=1', {ldelim}name:'edit_{$cat3->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="{$lang.fif1}" title="{$lang.fif1}" /></a> <a href="/filters/remove-{$cat3->id}.html" onclick="return get_window('/filters/remove-{$cat3->id}.html?window=1', {ldelim}name:'delete_{$cat3->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="{$lang.fyf1}" title="{$lang.fyf1}" /></a></span></li>
        {else}
        	<li><span class="folder" id="c_{$cat3->id}" title="{$lang.fds}: {$cat3->post_date}">{$cat3->name} <a href="/filters/add_filter-{$cat3->id}.html" onclick="return get_window('/filters/add_filter-{$cat3->id}.html?window=1');"><img src="/images/icons/action_paste.gif" alt="{$lang.fdf1}" title="{$lang.fdf1}" /></a> <a href="/filters/edit_sub-{$cat3->id}.html" onclick="return get_window('/filters/edit_sub-{$cat3->id}.html?window=1', {ldelim}name:'edit_{$cat3->id}'{rdelim});"><img src="/images/icons/page_edit.gif" alt="{$lang.fir1}" title="{$lang.fir1}" /></a> <a href="/filters/remove-{$cat3->id}.html" onclick="return get_window('/filters/remove-{$cat3->id}.html?window=1', {ldelim}name:'delete_{$cat3->id}'{rdelim});"><img src="/images/icons/folder_delete.gif" alt="{$lang.fyr1}" title="{$lang.fyr1}" /></a></span>
            {foreach from=$cat3->sub item=cat4 name=cat4}
            {if $smarty.foreach.cat4.first}<ul>{/if}
            <li><span class="file" id="c_{$cat4->id}" title="{$lang.fds}: {$cat4->post_date}">{$cat4->name} ({$cat4->host}) <a href="/filters/edit_filter-{$cat4->id}.html" onclick="return get_window('/filters/edit_filter-{$cat4->id}.html?window=1', {ldelim}name:'edit_{$cat4->id}'{rdelim});"><img src="/images//icons/page_edit.gif" alt="{$lang.fif1}" title="{$lang.fif1}" /></a> <a href="/filters/remove-{$cat4->id}.html" onclick="return get_window('/filters/remove-{$cat4->id}.html?window=1', {ldelim}name:'delete_{$cat4->id}'{rdelim});"><img src="/images/icons/page_delete.gif" alt="{$lang.fyf1}" title="{$lang.fyf1}" /></a></span></li>
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
{if $Cur.ajax ne 1}</div>{/if}