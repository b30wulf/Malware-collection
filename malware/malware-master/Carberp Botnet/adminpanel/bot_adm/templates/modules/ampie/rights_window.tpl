<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.gri}';
</script>

<div style="overflow:auto; max-height: 600px">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<th>Operating system</th>
<th>Not Admin</th>
<th>Admin</th>
{foreach from=$list item=item key=key name=list}
{if $smarty.foreach.list.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr id="tr_{$key}" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; cursor:pointer;">
<th>{if $key eq ''}Unknow{else}{$key}{/if}</th>
<th>{$item.0|procent:$item.all}% ({$item[0]})</th>
<th>{$item.1|procent:$item.all}% ({$item[1]})</th>
</tr>
{/foreach}
</table>
</div>