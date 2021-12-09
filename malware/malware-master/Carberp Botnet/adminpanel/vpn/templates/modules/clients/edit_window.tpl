<div id="a{$rand_name}"></div>
{if $save eq ''}
<form action="/clients/edit-{$client->id}.html?window=1" name="edit{$rand_name}" id="edit{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
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
    <th>{$client->name}</th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th style=" width: 200px;">{$lang.desc}</th>
    <th><input name="desc" type="text" value="{$client->desc}" class="input_obligatory" maxlength="32" {if $_SESSION.user->config.infoacc eq '1'}readonly="readonly"{/if} /></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
  <th>{$lang.enable}</th>
  <th><select name="enable" class="input_obligatory"><option value="no"{if $client->enable ne 1} selected="selected"{/if}>{$lang.no}</option><option value="1"{if $client->enable eq 1} selected="selected"{/if}>{$lang.yes}</option></select></th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
  <th>{$lang.server}</th>
  <th><select name="server" class="input_obligatory">
  {if $_SESSION.user->config.infoacc ne '1'}<option value="0"{if $smarty.post.server eq 0} selected="selected"{/if}>{$lang.autoprio}</option>{/if}
  {foreach from=$servers item=item}
  <option value="{$item->id}"{if $client->server eq $item->id} selected="selected"{/if}>{$item->ip} - {$item->name}</option>
  {/foreach}
  </select></th>
</tr>
<tr class="bgp1"{if $_SESSION.user->config.infoacc eq '1'} style="visibility:hidden"{/if}><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2"{if $_SESSION.user->config.infoacc eq '1'} style="visibility:hidden"{/if}>
  <th>{$lang.autocheck}</th>
  <th><input type="checkbox" name="autocheck" {if $_SESSION.user->config.infoacc eq '1'}disabled="disabled"{/if} {if $client->autocheck eq 1} checked="checked"{/if} /></th>
</tr>
<tr class="bgp1"{if $_SESSION.user->config.infoacc eq '1'} style="visibility:hidden"{/if}><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="{$lang.edit}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>

</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['edit{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.edits}';
function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['edit{$rand_name}'].action,
		method: document.forms['edit{$rand_name}'].method,
		form: 'edit{$rand_name}',
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