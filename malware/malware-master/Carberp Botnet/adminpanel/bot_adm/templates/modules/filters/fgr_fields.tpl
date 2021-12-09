<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var disable_window = 'fgr_fields_wid';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.fvpf} <span style="font-size:10px;">({$lang.fr}: {$filter->name})</span>';
//function set_value(){ldelim}
$('#fgr{$rand_name} input').each(
function(n,obj){ldelim}
if(document.forms['filters']['fgr_fields'].value.indexOf(obj.name + ',', 0) != -1) obj.checked =  true;
{rdelim}
);

//{rdelim};
</script>
<form action="#" name="fgr{$rand_name}" id="fgr{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="return false;">
<br />
<div align="center"><input type="text" name="add_val{$rand_name}" /> <input type="button"  onclick="add_fgr_val('fgr{$rand_name}', 'add_val{$rand_name}');" value="{$lang.fdsp}" /></div>
<br />
<div align="center">{$lang.fspdf}</div>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /></div>

<table cellspacing="1" cellpadding="0" style="width: 100%; min-width: 500px; padding: 10px;">
<tr class="bgp3">
	<td style="width: 100%; text-align: center">{$lang.fnp}</td>
    <td style="width: 50px; text-align: center"></td>
</tr>
{foreach from=$filter->fields item=item key=key name=fields}
{if $item ne ''}
{if $smarty.foreach.fields.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="font-size: 10px;">
	<th>{$item}</th>
	<th><input name="{$item}" type="checkbox" onclick="add_fgr(this);" /></th>
</tr>
{/if}
{/foreach}
</table>

<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /></div>

<br />

</form>