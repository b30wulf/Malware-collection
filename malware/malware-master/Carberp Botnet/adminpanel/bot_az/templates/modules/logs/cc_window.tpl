<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$Cur.str}';
</script>


<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp2">
    <th width="40%">Система (Баланс)</th>
    <th width="60%">Лог</th>
</tr>
{foreach from=$log item=item name="log"}
{if $smarty.foreach.log.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th width="40%">{$item->subsys} {if $item->balance ne ''}({$item->balance}){/if}</th>
    <th width="60%"><textarea style="width: 99%; min-height: 40px">{$item->log}</textarea></th>
</tr>
{/foreach}
</table>

<br />