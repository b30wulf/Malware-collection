<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var count_{$rand_name} = '{$log->data|@count}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$log->prefix}{$log->uid}';

function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['save{$rand_name}'].action,
		method: document.forms['save{$rand_name}'].method,
		form: 'save{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};

function add_{$rand_name}(){ldelim}
	var tbl = document.getElementById('tal_{$rand_name}');
	var ci = (count_{$rand_name}-1);
	
	var _t = tbl.insertRow(tbl.rows.length);
	
	if(tbl.rows[tbl.rows.length-2].className == 'bgp2'){
		_t.className = 'bgp1';
	}else{
		_t.className = 'bgp2';
	}
	
	var _c0 = _t.insertCell(0);
	var _c1 = _t.insertCell(1);
	var _c2 = _t.insertCell(2);
	var _c3 = _t.insertCell(3);
	
	_c0.align = 'center';
	
	_c0.innerHTML = Number(count_{$rand_name}) + 1;
	_c1.innerHTML = '<input name="data['+count_{$rand_name}+'][paysumm]" type="text" style="width: 100%;" value="" />';
	_c2.innerHTML = '<input name="data['+count_{$rand_name}+'][paydescr]" type="text" style="width: 100%;" value="" />';
	_c3.innerHTML = '<input name="data['+count_{$rand_name}+'][paydate]" type="text" style="width: 100%;" value="" />';
	
	count_{$rand_name} = Number(count_{$rand_name}) + 1;
{rdelim};

</script>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp2">
    <th width="40%">Login</th>
    <th width="60%">{if $log->login ne ''}{$log->login}{else}-{/if}</th>
</tr>
<tr class="bgp1">
    <th width="40%">Password</th>
    <th width="60%">{if $log->password ne ''}{$log->password}{else}-{/if}</th>
</tr>
<tr class="bgp2">
    <th width="40%">Сумма</th>
    <th width="60%">{if $log->summ ne ''}{$log->summ}{else}-{/if}</th>
</tr>
</table>

<br /><br />

<form action="/logs/rafa-{$log->prefix}{$log->uid}.html?window=1" name="save{$rand_name}" id="save{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">

<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc;" id="tal_{$rand_name}">
<tr class="bgp3" style="text-align: center">
<td style="width:1px">#</td>
<td>Сумма</td>
<td>Описание</td>
<td>Дата</td>
</tr>

{foreach from=$log->data key=key item=item}
<tr class="bgp2">
<th>{$key+1}</th>
<th><input name="data[{$key}][paysumm]" type="text" style="width: 100%;" value="{$item.paysumm}" /></th>
<th><input name="data[{$key}][paydescr]" type="text" style="width: 100%;" value="{$item.paydescr}" /></th>
<th><input name="data[{$key}][paydate]" type="text" style="width: 100%;" value="{$item.paydate}" /></th>
</tr>
{/foreach}

</table>
</form>
<br /><br />

<div style="padding: 10px;"><input type="button" style="width: 89%" value="Сохранить" onclick="submit_{$rand_name}();" /> <input type="button" style="width: 10%" value="+1" onclick="add_{$rand_name}();" /></div>

<br />