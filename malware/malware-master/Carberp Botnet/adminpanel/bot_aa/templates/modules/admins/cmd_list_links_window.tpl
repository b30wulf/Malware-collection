<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">

<table cellspacing="1" cellpadding="5" style="width:100%; font-size:10px; text-align: center;" >
<tr class="bg3">
	<td style="width:10px;">#</td>
    <td>Ссылка</td>
    <td style="width:10px;">Скрытая</td>
    <td style="width:10px;">&nbsp;</td>
  </tr>
{foreach from=$cmds item=cmd name=list_cmds}
{if $smarty.foreach.list_cmds.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{if $cmd->dev eq 1}dev{/if}{$bg}" onMouseMove="this.className = 'bg4'" onMouseOut="this.className = 'bg{if $cmd->dev eq 1}dev{/if}{$bg}'" {if $cmd->enable eq 0}style="background-color: #666#F0F">{/if}
    <th style="font-size:9px">{$cmd->id}</th>
    <th style="font-size:9px">{$cmd->link}</th>
    <th style="font-size:9px">{if $cmd->dev eq 1}Да{else}Нет{/if}</th>
    <th><a href="#" onclick="return delete_link('{$admin->id}', '{$cmd->id}',document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));"><img src="/images/delete.png" alt="Удалить ссылку" title="Удалить ссылку" border="0"></a></th>
  </tr>
{/foreach}
</table>

</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Список ссылок  - {$admin->link}';

</script>