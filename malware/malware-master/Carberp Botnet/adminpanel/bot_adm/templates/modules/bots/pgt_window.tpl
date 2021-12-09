{if $save eq ''}
<form action="/bots/pgt.html?window=1" name="add{$rand_name}" id="add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<br />
<div align="center">
{$errors}
</div>
<br />
{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 150px;">{$lang.uids}</th>
    <th style="text-align: left;"><textarea name="uids" id="uids" class="user" style="min-height: 200px; width: 100%">{$smarty.post.uids}</textarea></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.cmd}</th>
    <th style="text-align: left;"><input name="cmd" type="text" value="{$smarty.post.cmd}" class="user" style="width:100%" maxlength="120" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.pgt_title}';
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
<table cellspacing="1" cellpadding="5" style="width: 100%; border: 1px solid #cccccc; font-size:10px; text-align:center">
<tr class="bgp3">
	<td style="width: 60%;">{$lang.uid}</td>
	<td style="width: 40%;">{$lang.status}</td>
</tr>
{foreach from=$b item=item key=key name=bot}
{if $smarty.foreach.bot.iteration is not even}{assign var=bg value=2}{else}{assign var=bg value=1}{/if}
<tr class="bgp{$bg}">
	<td>{$key}</td>
	<td>{if $item eq 1}Ok!{else}<font color="#FF0000">Not!</font>{/if}</td>
</tr>
{/foreach}
</table>
{/if}