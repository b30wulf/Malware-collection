<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.fnsf} <span style="font-size:10px;">({$lang.fvz}: {$count_items})</span>';
var unnecessar_name_form = 'search{$rand_name}';
function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['search{$rand_name}'].action,
		method: document.forms['search{$rand_name}'].method,
		form: 'search{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};
</script>
<form action="/filters/unnecessary.html?window=1" name="search{$rand_name}" id="search{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
<div align="center">{$lang.fdhksos}:<br /><textarea cols="1" rows="2" name="name" class="user" style="width: 800px; resize:none;">{$smarty.post.name}</textarea><br /><input name="submit" type="button" value="{$lang.search}" class="user" onclick="submit_{$rand_name}();" style="width: 800px;" /></div>
</form>
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="text-align: center">#</td>
	<td style="width: 100%; text-align: center">{$lang.fdh}</td>
    <td style="text-align: center">{$lang.types}</td>
    <td style="text-align: center"></td>
    <td style="text-align: center"></td>
</tr>
{foreach from=$unnecessary item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="font-size: 10px;">
	<th>{$item->id}</th>
	<th>{$item->host}</th>
    <th>{if $item->type eq '6'}Граббер{elseif $item->type eq '5'}Формграббер{/if}</th>
    <th><a href="/filters/unnecessary_download-{$item->id}.html" target="_blank">{$lang.show}</a></th>
    <th><a href="/filters/unnecessary_download-{$item->id}.html?type=1" target="_blank">{$lang.dl}</a></th>
</tr>
{/foreach}
</table>
<br />
<div style="font-size:10px" align="center">&nbsp;{$pages}&nbsp;</div>