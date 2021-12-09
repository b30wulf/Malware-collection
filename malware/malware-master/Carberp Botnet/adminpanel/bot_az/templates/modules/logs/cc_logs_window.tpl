<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$Cur.str}';
</script>

{foreach from=$log item=item}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">

<tr class="bgp2">
    <th width="40%">Система</th>
    <th width="60%">{$item->subsys}</th>
</tr>

<tr class="bgp2">
    <th width="40%">Баланс</th>
    <th width="60%">{$item->balance}</th>
</tr>

{foreach from=$item->log key=key item=var name="log"}
{if $smarty.foreach.log.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th width="40%">{$key}</th>
    <th width="60%">{$var}</th>
</tr>
{/foreach}
</table>
<br />
{/foreach}

<br />