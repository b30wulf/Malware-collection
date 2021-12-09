<table cellpadding="0" cellspacing="0" border="0" style="width:100%;"><tr>

<td id="child_content" align="center"><hr />{$lang.fsv}
{php}if(file_exists('cache/imports/data.json')){
echo '<hr />';
$idata = json_decode(file_get_contents('cache/imports/data.json'), false);
echo '<font color="#FF0000">{$lang.vsip}</font>';
if($idata->all > 0){
$idata->made = $idata->all - $idata->rests;
$idata->remain = $idata->all - $idata->made;
echo '<hr />';
echo '<font color="#999999">';
echo '{$lang.obra}: '.$idata->made.' из '.$idata->all . ' ('.number_format(($idata->made / $idata->all * 100), 2).'%)';
echo '<hr />';
echo '{$lang.osta}: '.($idata->remain) . ' ('.number_format(($idata->remain / $idata->all * 100), 2).'%)';
echo '<hr />';
echo '{$lang.obrab}: '.$idata->cur;
echo '</font>';
echo '<hr />';
}
}else{
echo '<hr />';
}
{/php}
{if $proc ne ''}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bg4" style="border: 1px solid #FFFFFF" align="center">
    <td>Размер файла</td>
    <td>Обработано</td>
    <td>Завершено</td>
    <td>Текущих записей</td>
    <td>Обработано записей</td>
    <td>Завершено</td>
</tr>
{$proc}
</table>
{/if}
</td>

<td id="sub_menu">

<ul id="cats" class="filetree">
<!--
<li style="font-weight:bold">{$lang.fsf}{if $smarty.session.user->access.filters.edit eq on}<a href="/filters/edit.html"><img src="/images/edit.png" title="{$lang.edit}" alt="{$lang.edit}" /></a>{/if}</li>
<li><span class="file" id="c_search" onclick="get_window('/filters/search.html?window=1', {ldelim}name: 'search'{rdelim});">{$lang.fgs}</span></li>
-->
<!--
<li><span class="file" id="c_savelog" onclick="get_window('/filters/savelog.html?window=1', {ldelim}name: 'savelog',height: 650{rdelim});">{$lang.fsl}</span></li>
-->
{foreach from=$catalog item=cat1 name=cat1}
{if $cat1->host ne ''}
	<li><span class="file" id="c_{$cat1->id}" onclick="get_item('{$cat1->id}');">{$cat1->name}</span></li>
{else}
	<li><span class="folder">{$cat1->name}</span>
	{foreach from=$cat1->sub item=cat2 name=cat2}
    {if $smarty.foreach.cat2.first}<ul>{/if}
    {if $cat2->host ne ''}
    	<li><span class="file" id="c_{$cat2->id}" onclick="get_item('{$cat2->id}');">{$cat2->name}</span></li>
    {else}
    	<li><span class="folder">{$cat2->name}</span>
        {foreach from=$cat2->sub item=cat3 name=cat3}
        {if $smarty.foreach.cat3.first}<ul>{/if}
        {if $cat3->host ne ''}
        	<li><span class="file" id="c_{$cat3->id}" onclick="get_item('{$cat3->id}');">{$cat3->name}</span></li>
        {else}
        	<li><span class="folder">{$cat3->name}</span>
            {foreach from=$cat3->sub item=cat4 name=cat4}
            {if $smarty.foreach.cat4.first}<ul>{/if}
            <li><span class="file" id="c_{$cat4->id}" onclick="get_item('{$cat4->id}');">{$cat4->name}</span></li>
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
{if $_SESSION['user']->access['filters']['logs_static'] eq 'on'}
<li><span class="file" id="c_me" onclick="get_item_static('me');">{$lang.fms}</span></li>
<li><span class="file" id="c_ft" onclick="get_item_static('ft');">{$lang.ftpc}</span></li>
<li><span class="file" id="c_ep" onclick="get_item_static('ep');">{$lang.fpp}</span></li>
<li><span class="file" id="c_rd" onclick="get_item_static('rd');">{$lang.frdc}</span></li>
{/if}
</ul>

</td>

</tr></table>