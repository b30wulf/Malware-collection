<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');

function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['f{$rand_name}'].action,
		method: document.forms['f{$rand_name}'].method,
		form: 'f{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim}

</script>

<div>
<form method="post" name="f{$rand_name}" id="f{$rand_name}" action="/cabs/sclear.html?window=1" enctype="application/x-www-form-urlencoded">

<select name="types[]" multiple="multiple" style="width:100%; height: 500px;">
{foreach from=$items item=item name=items}
<option value="{$item->md5_type}">{$item->type}</option>
{/foreach}
</select>
<input type="button" onclick="submit_{$rand_name}();" value="удалить" style="width:100%;" />
</form>
</div>