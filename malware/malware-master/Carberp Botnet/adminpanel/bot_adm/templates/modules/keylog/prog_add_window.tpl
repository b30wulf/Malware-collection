{if $save eq ''}
<form action="/keylog/prog_add.html?window=1" name="prog_add{$rand_name}" id="prog_add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<br />
<div align="center">
{$errors}
</div>
<br />
{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 150px;">{$lang.name}</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" maxlength="120" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.hashprog}</th>
    <th style="text-align: left;"><input name="hash" type="text" value="{$smarty.post.hash}" class="user" maxlength="120" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['prog_add{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.add_prog}';
function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['prog_add{$rand_name}'].action,
		method: document.forms['prog_add{$rand_name}'].method,
		form: 'prog_add{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};
</script>
{else}
<center><h2 style="color:#000">{$lang.kdd}</h2></center>
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.zakr}</a></center>
{/if}