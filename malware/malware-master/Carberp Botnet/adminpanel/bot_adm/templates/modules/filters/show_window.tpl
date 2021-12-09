<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.fil} #{$log->id}';
</script>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2" s>
    <th style="text-align: left; width: 130px;">{$lang.bot}</th>
    <th style="text-align: left;">{$log->prefix}{$log->uid}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.coutry}</th>
    <th style="text-align: left;">{$log->country}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.program}</th>
    <th style="text-align: left;">{$log->program}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.types}</th>
    <th style="text-align: left;">{if $log->type eq 5}{$lang.type.5}{else if $log->type eq 6}{$lang.type.6}{else}{$log->type}{/if}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.fdd}</th>
    <th style="text-align: left;">{$log->post_date}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.fli}</th>
    <th style="text-align: left;">{$log->url|urldecode}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.fdan}</th>
    <th style="text-align: left;"><textarea style="width:100%; height: 100px; resize: none" readonly="readonly">{$log->data|urldecode}</textarea></th>
</tr>
</table>