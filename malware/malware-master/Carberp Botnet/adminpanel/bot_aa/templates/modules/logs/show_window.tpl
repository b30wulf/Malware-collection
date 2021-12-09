<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Информация о логе #{$log->id}';
</script>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2" s>
    <th style="text-align: left; width: 130px;">Префикс</th>
    <th style="text-align: left;">{$log->prefix}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">UID</th>
    <th style="text-align: left;">{$log->uid}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Страна</th>
    <th style="text-align: left;">{$log->country}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Программа</th>
    <th style="text-align: left;">{$log->program}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Дата добавления</th>
    <th style="text-align: left;">{$log->post_date}</th>
</tr>
{foreach from=$filter->fields->name item="item" name="ditem"}
{assign var='tvar' value="v`$smarty.foreach.ditem.iteration`"}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$item}</th>
    <th style="text-align: left;">{$log->$tvar}</th>
</tr>
{/foreach}
</table>