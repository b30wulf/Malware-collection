<div id="a{$rand_name}"></div>
{if $save eq ''}
<form action="/servers/edit-{$item->id}.html?window=1" name="add{$rand_name}" id="add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
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
    <th style=" width: 200px;">{$lang.name}</th>
    <th><input name="name" type="text" value="{$smarty.post.name}" class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.protocol}</th>
    <th><select name="protocol" class="input_obligatory">
    <option value="udp" {if $smarty.post.protocol eq 'udp'}selected="selected"{/if}>{$lang.udp}</option>
    <option value="tcp" {if $smarty.post.protocol eq 'tcp'}selected="selected"{/if}>{$lang.tcp}</option>
    </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.ip}</th>
    <th><input name="ip" type="text" value="{$smarty.post.ip}" class="input_obligatory" maxlength="20" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.port}</th>
    <th><input name="port" type="text" value="{$smarty.post.port}" class="input_obligatory" maxlength="5" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.ca}</th>
    <th><textarea name="ca" class="input_obligatory" style="height:150px">{$smarty.post.ca}</textarea></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.crt}</th>
    <th><textarea name="crt" class="input_obligatory" style="height:150px">{$smarty.post.crt}</textarea></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.key}</th>
    <th><textarea name="key" class="input_obligatory" style="height:150px">{$smarty.post.key}</textarea></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.ta}</th>
    <th><textarea name="ta" class="input_obligatory" style="height:150px">{$smarty.post.ta}</textarea></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.cfg}</th>
    <th><textarea name="cfg" class="input_obligatory" style="height:200px">{$smarty.post.cfg}</textarea></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.enable}</th>
    <th><select name="enable" class="input_obligatory">
    <option value="0" {if $smarty.post.enable eq '0'}selected="selected"{/if}>{$lang.no}</option>
    <option value="1" {if $smarty.post.enable eq '1'}selected="selected"{/if}>{$lang.yes}</option>
    </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>

</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.edits} - {$item->ip}';
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
</script>
{else}
<script language="javascript" type="application/javascript">
document.getElementById('content').innerHTML = '<div align="center"><img src="/images/indicator.gif"></div>';
window_close(document.getElementById('a{$rand_name}').parentNode.parentNode.id, 1);
{literal}hax('/servers/index.html?ajax=1',{id: 'content',nohistory:true,nocache:true,destroy:true,rc:true}){/literal}
</script>
<center><h2 style="color:#000">{$lang.kdd}</h2></center>
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.zakr}</a></center>
{/if}