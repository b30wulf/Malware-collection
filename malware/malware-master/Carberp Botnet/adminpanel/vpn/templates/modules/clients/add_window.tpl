<div id="a{$rand_name}"></div>
{if $save eq ''}
<form action="/clients/add.html?window=1" name="add{$rand_name}" id="add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
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
    <th style=" width: 200px;">{$lang.desc}</th>
    <th><input name="desc" type="text" value="{$smarty.post.desc}" class="input_obligatory" maxlength="32" /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th style=" width: 200px;">{$lang.days}</th>
    <th>
    <select name="days" class="input_obligatory">
    <option value="1">1 день</option>
    <option value="7">7 дней</option>
    <option value="30">30 дней</option>
    <option value="60">60 дней</option>
    <option value="120">120 дней</option>
    <option value="240">240 дней</option>
    <option value="365" selected="selected">365 дней</option>
    </select>
    </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
  <th>{$lang.enable}</th>
  <th><select name="enable" class="input_obligatory"><option value="0">{$lang.no}</option><option value="1" selected="selected">{$lang.yes}</option></select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
  <th>{$lang.server}</th>
  <th><select name="server" class="input_obligatory">
  <option value="0"{if $smarty.post.server eq 0} selected="selected"{/if}>{$lang.autoprio}</option>
  {foreach from=$servers item=item}
  <option value="{$item->id}"{if $smarty.post.server eq $item->id} selected="selected"{/if}>{$item->ip} - {$item->name}</option>
  {/foreach}
  </select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
  <th>{$lang.autocheck}</th>
  <th><input type="checkbox" name="autocheck"{if $smarty.post.autocheck eq 1} checked="checked"{/if} /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>

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
{literal}hax('/clients/index.html?ajax=1',{id: 'content',nohistory:true,nocache:true,destroy:true,rc:true}){/literal}
</script>
<center><h2 style="color:#000">{$lang.kdd}</h2></center>
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.zakr}</a></center>
{/if}