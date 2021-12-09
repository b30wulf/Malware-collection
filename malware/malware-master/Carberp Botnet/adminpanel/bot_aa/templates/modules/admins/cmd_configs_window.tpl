<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">

<table cellspacing="1" cellpadding="5" style="width:100%; font-size:10px; text-align: center;" >
<tr class="bg3">
	<td style="width: 50%; text-align: center">Название файла</td>
	<td style="width: 20%; text-align: center">Размер</td>
	<td style="width: 30%; text-align: center">Дата создания</td>
</tr>
{foreach from=$files item=file name=files}
{if $smarty.foreach.files.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onMouseMove="this.className = 'bg4'" onMouseOut="this.className = 'bg{$bg}'">
    <th><a href="http://{$admin->link}/{$file->link}" target="_blank">{$file->name}</a></th>
	<th>{$file->size|size_format}</th>
	<th>{$file->date|TimeStampToStr}</th>
  </tr>
{/foreach}
</table>

</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Список ссылок  - {$admin->link}';

</script>