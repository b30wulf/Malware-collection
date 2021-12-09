<div id="a{$rand_name}"></div>
{if $save eq ''}
<form action="/systems/add.html?window=1" name="add{$rand_name}" id="add{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<br />
<div align="center">
{$errors}
</div>
<br />
{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 200px;">{$lang.nid}</th>
    <th style="text-align: left;"><input name="nid" type="text" value="{$smarty.post.nid}" class="user" maxlength="8" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.name}</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" maxlength="125" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.percent}</th>
    <th style="text-align: left;"><input name="percent" type="text" value="{$smarty.post.percent}" class="user" maxlength="2" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.format} (<a href="#null"  onclick="get_window('/systems/index.html?str=format_info', {ldelim}name:'zi', widht: 800, height:500, top:0{rdelim}); document.getElementById('zi_wid').style.top = '10px';">?</a>)</th>
    <th style="text-align: left;"><textarea name="format" class="user" rows="8" cols="20">{$smarty.post.format}</textarea></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
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
{literal}hax('/systems/index.html?ajax=1',{id: 'content',nohistory:true,nocache:true,destroy:true,rc:true}){/literal}
</script>
<center><h2 style="color:#000">{$lang.kdd}</h2></center>
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.zakr}</a></center>
{/if}