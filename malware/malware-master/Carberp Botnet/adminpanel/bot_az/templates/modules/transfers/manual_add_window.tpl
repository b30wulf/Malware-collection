<div id="a{$rand_name}"></div>
{if $save eq ''}
<form action="/transfers/manual_add.html?window=1" name="add{$rand_name}" id="add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<br />
<div align="center">
{$errors}
</div>
<br />
{/if}

<input name="system" type="hidden" value="{$smarty.post.system}" />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: left; ">
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">{$lang.systems}</th>
    <th style="text-align: left;">
    <select name="system" class="user">
    {foreach from=$systems item=s name=systems}
    <option value="{$s->nid}" {if isset($item->system[$s->nid])}selected="selected"{/if}>{$s->name}</option>
    {/foreach}
    </select>
    </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.keysh}</th>
    <th><input name="key" type="text" value='{$smarty.post.key}' class="input_obligatory" maxlength="120" /></th>
</tr>
</table>

<br /><div style="text-align: center; font-weight: bold">Первый пассив <input type="button" onclick="oa_add{$rand_name}(1);" value="+1"  /></div><br />

<table id="one_arr{$rand_name}" cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: left; ">
<!--
<tr class="bgp2">
    <th style="width: 200px;">{$lang.acc} #1</th>
    <th><input name="[1][0][acc]" type="text" class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp2">
    <th>{$lang.summ} #1</th>
    <th><input name="[1][0][summ]" type="text" class="input_obligatory" maxlength="120" /></th>
</tr>
-->
</table>


<br /><div style="text-align: center; font-weight: bold">Второй пассив <input type="button" onclick="oa_add{$rand_name}(2);" value="+1"  /></div><br />

<table id="two_arr{$rand_name}" cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: left; ">
<!--
<tr class="bgp2">
    <th style="width: 200px;">Счет #1</th>
    <th><input name="[2][0][acc]" type="text" class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp3">
    <th style="width: 200px;">Номер документа #1</th>
    <th><input name="[2][0][num]" type="text" class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp3">
    <th style="width: 200px;">Дата перевода #1</th>
    <th><input name="[2][0][date] type="text" class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp2">
    <th>{$lang.summ} #1</th>
    <th><input name="[2][0][summ]" type="text" class="input_obligatory" maxlength="120" /></th>
</tr>
-->
</table>


<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: left; ">
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>

</form>

<script language="javascript" type="application/javascript">
    
var id_{$rand_name} = document.forms['add{$rand_name}'].parentNode.id.replace('_content', '');

document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.manual_add}';

function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['add{$rand_name}'].action,
		method: document.forms['add{$rand_name}'].method,
		form: 'add{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};

function add_row{$rand_name}(text, input, mt, rl){ldelim}            
    var newRow=mt.insertRow(0);
    
    if(rl & 1 == 1){ldelim}
	var className = 'bgp3';
    {rdelim}else{ldelim}
	var className = 'bgp2';
    {rdelim}
    
    newRow.className = className;
    
    var eTh1 = document.createElement('th');
    eTh1.width="200";
    eTh1.innerHTML=text+" #" + (rl+1);
    
    var eTh2 = document.createElement('th');
    
    var newInput=document.createElement('input');
    newInput.name = input;
    newInput.type = 'text';
    newInput.className = 'input_obligatory';
    
    eTh2.appendChild(newInput);
    
    //eTh2.innerHTML=input;
    
    newRow.appendChild(eTh1);
    newRow.appendChild(eTh2);
}

function oa_add{$rand_name}(type){ldelim}
    if(type == 1){ldelim}
	var mt = document.getElementById("one_arr{$rand_name}");
	var rl = mt.rows.length/2;
	
	add_row{$rand_name}('{$lang.summ}', 'bl[1]['+rl+'][summ]', mt, rl);
	add_row{$rand_name}('Счет', 'bl[1]['+rl+'][acc]', mt, rl);
    {rdelim}else if(type == 2){ldelim}
	var mt = document.getElementById("two_arr{$rand_name}");
	var rl = mt.rows.length/4;
	
	add_row{$rand_name}('{$lang.summ}', 'bl[2]['+rl+'][summ]', mt, rl);
	add_row{$rand_name}('Дата перевода', 'bl[2]['+rl+'][date]', mt, rl);
	add_row{$rand_name}('Номер документа', 'bl[2]['+rl+'][num]', mt, rl);
	add_row{$rand_name}('Счет', 'bl[2]['+rl+'][acc]', mt, rl);	
    {rdelim}
{rdelim}

</script>
{else}
<script language="javascript" type="application/javascript">
document.getElementById('content').innerHTML = '<div align="center"><img src="/images/indicator.gif"></div>';
window_close(document.getElementById('a{$rand_name}').parentNode.parentNode.id, 1);
{literal}hax('/transfers/manual.html?ajax=1',{id: 'content',nohistory:true,nocache:true,destroy:true,rc:true}){/literal}
</script>
<center><h2 style="color:#000">{$lang.kdd}</h2></center>
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.zakr}</a></center>
{/if}