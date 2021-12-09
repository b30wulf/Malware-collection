<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Перемещение фильтра {$item->name} {if $parent}<span style="font-size:10px;">(родитель:{$parent->name})</span>{/if}';

function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['cut_filter{$rand_name}'].action,
		method: document.forms['cut_filter{$rand_name}'].method,
		form: 'cut_filter{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim}

</script>
{if $save eq ''}
<form action="/catalog/cut_filter-{$Cur.id}.html?window=1" name="cut_filter{$rand_name}" id="cut_filter{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
<br />
<div style="width: 500px; padding-left: 16px;">

{foreach from=$list item=cat1 name=cat1}
    <div style="padding-left: 10px; line-height: 25px"><input id="r{$cat1->id}" type="radio" name="list" value="{$cat1->id}" {if $parent->id eq $cat1->id}disabled="disabled"{/if} /> <label for="r{$cat1->id}" >{$cat1->name}</label></div> 
    {foreach from=$cat1->sub item=cat2 name=cat2}
    <div style="padding-left: 30px; line-height: 25px"><input id="r{$cat2->id}" type="radio" name="list" value="{$cat2->id}" {if $parent->id eq $cat2->id}disabled="disabled"{/if} /> <label for="r{$cat2->id}" >{$cat2->name}</label></div>
    {foreach from=$cat2->sub item=cat3 name=cat3}
    <div style="padding-left: 50px; line-height: 25px"><input id="r{$cat3->id}" type="radio" name="list" value="{$cat3->id}" {if $parent->id eq $cat3->id}disabled="disabled"{/if} /> <label for="r{$cat3->id}" >{$cat3->name}</label></div>
{/foreach}
{/foreach}
{/foreach}
<br />
<input name="submit" type="button" value="Изменить" class="user" onclick="submit_{$rand_name}();" />
<br /><br />
</div>
</form>

{/if}