<div id="a{$rand_name}"></div>
{if $save eq ''}
<form action="/drops/add.html?window=1" name="add{$rand_name}" id="add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<br />
<div align="center">
{$errors}
</div>
<br />
{/if}

{if $smarty.post.system|count eq 0}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 200px;">{$lang.systems}</th>
    <th style="text-align: left;">
    <select name="system[]" class="user" style="height: 300px" multiple="multiple">
    {foreach from=$systems item=s name=systems}
    <option value="{$s->nid}">{$s->name}</option>
    {/foreach}
    </select>
    </th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
    <th colspan="2"><input name="set" type="button" value="{$lang.set}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
{else}
<input name="system" type="hidden" value="{$smarty.post.system}" />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc; text-align: left; ">
<tr class="bgp2">
    <th style=" width: 200px;">{$lang.name}</th>
    <th><input name="name" type="text" value='{$smarty.post.name}' class="input_obligatory" maxlength="120" /></th>
</tr>
<!--tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.dkppb}</th>
    <th><input name="other[kppb]" type="text" value='{$smarty.post.other.kppb}' class="inputs" maxlength="120" /></th>
</tr-->
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.cityb}</th>
    <th><input name="citybank" type="text" value='{$smarty.post.citybank}' class="inputs" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.dbik}</th>
    <th><input name="other[bik]" type="text" value='{$smarty.post.other.bik}' class="inputs" maxlength="9" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.dsbp}</th>
    <th><input name="other[BnkKOrrAcnt]" type="text" value='{$smarty.post.other.BnkKOrrAcnt}' class="inputs" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.inn}</th>
    <th><input name="other[inn]" type="text" value='{$smarty.post.other.inn}' class="inputs" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.dkppp}</th>
    <th><input name="other[kppp]" type="text" value='{$smarty.post.other.kppp}' class="inputs" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.receiver}</th>
    <th><input name="receiver" type="text" value='{$smarty.post.receiver}' class="input_obligatory" maxlength="1024" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.destination}</th>
    <th><input name="destination" type="text" value='{$smarty.post.destination}' class="input_obligatory" maxlength="250" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.acc} {$lang.bt}</th>
    <th><input name="acc" type="text" value='{$smarty.post.acc}' class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.vat}</th>
    <th><select name="vat" class="input_obligatory">
    <option value="0" {if $smarty.post.vat eq 0}selected="selected"{/if}>0%</option><option value="18" {if $smarty.post.vat eq 18}selected="selected"{/if}>18%</option></select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.from}</th>
    <th><input name="from" type="text" value='{$smarty.post.from}' class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.to}</th>
    <th><input name="to" type="text" value='{$smarty.post.to}' class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.max}</th>
    <th><input name="max" type="text" value='{$smarty.post.max}' class="input_obligatory" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.round}</th>
    <th><select name="other[round]" class="input_obligatory">
    <option value="0" {if $smarty.post.other.round eq 0}selected="selected"{/if}>{$lang.no}</option>
    <option value="1" {if $smarty.post.other.round eq 1}selected="selected"{/if}>{$lang.yes}</option>
    </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.test}</th>
    <th><select name="other[test]" class="input_obligatory">
    <option value="0" {if $smarty.post.other.test eq 0}selected="selected"{/if}>{$lang.no}</option>
    <option value="1" {if $smarty.post.other.test eq 1}selected="selected"{/if}>{$lang.yes}</option>
    </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.checknote}</th>
    <th><select name="check_note" class="input_obligatory">
    <option value="0" {if $smarty.post.check_note eq 0}selected="selected"{/if}>{$lang.no}</option>
    <option value="1" {if $smarty.post.check_note eq 1}selected="selected"{/if}>{$lang.yes}</option>
    </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.checkcity}</th>
    <th><select name="check_city" class="input_obligatory">
    <option value="0" {if $smarty.post.check_city eq 0}selected="selected"{/if}>{$lang.no}</option>
    <option value="1" {if $smarty.post.check_city eq 1}selected="selected"{/if}>{$lang.yes}</option>
    </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th>{$lang.cfgs}</th>
    <th><input name="other[cfgs]" type="text" value='{$smarty.post.other.cfgs}' class="inputs" maxlength="120" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="{$lang.dropsadd}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
{/if}
</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.adds}';
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
{literal}hax('/drops/index.html?ajax=1',{id: 'content',nohistory:true,nocache:true,destroy:true,rc:true}){/literal}
</script>
<center><h2 style="color:#000">{$lang.kdd}</h2></center>
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.zakr}</a></center>
{/if}