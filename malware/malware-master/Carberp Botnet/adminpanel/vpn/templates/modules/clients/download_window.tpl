<div id="dl{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('dl{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.download}: {$client->name}';
</script>
<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; text-align: center">
<tr class="bgp3">
<td style="width: 20%;">Тип</td>
<td style="width: 50%;">Инфо</td>
<td style="width: 30%;">Действие</td>
</tr>
<tr class="bgp1">
<td>Direct Download</td>
<td>-</td>
<td>{if $download.direct eq false}<a onclick="dsr('{$client->id}', 'direct', '{$rand_name}');">Создать файл</a>{else}<a onclick="dsr('{$client->id}', 'direct', '{$rand_name}');">Создать файл</a> / <a href="/clients/download-{$client->id}.html?x=direct" target="_blank">Загрузить файл</a>{/if}</td>
</tr>
<tr class="bgp2">
<td>SendSpace.com</td>
<td><textarea style="height: 120px; width:100%">{$download.sendspace.info}</textarea></td>
<td><a onclick="dsr('{$client->id}', 'sendspace', '{$rand_name}');">Создать и загрузить файл</a></td>
</tr>

</table>

</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['edit{$rand_name}'].parentNode.id.replace('_content', '');

document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.download}';
function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['edit{$rand_name}'].action,
		method: document.forms['edit{$rand_name}'].method,
		form: 'edit{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};
</script>