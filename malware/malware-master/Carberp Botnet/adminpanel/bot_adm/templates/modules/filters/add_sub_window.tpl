{if $save eq ''}
<form action="{if $parent}/filters/add_sub-{$Cur.id}.html?window=1{else}/filters/add_sub.html?window=1{/if}" name="add_sub{$rand_name}" id="add_sub{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<br />
<div align="center">
{$errors}
</div>
<br />
{/if}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{if $parent}
<tr class="bgp1">
    <th style="text-align: left; width: 80px;">{$lang.fvr}</th>
    <th style="text-align: left;">{$parent->name}</th>
</tr>
{/if}
<tr class="bgp2">
    <th style="text-align: left; width: 80px;">{$lang.fn}</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{if $parent}{$lang.fdpr}{else}{$lang.fdr}{/if}';
function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['add_sub{$rand_name}'].action,
		method: document.forms['add_sub{$rand_name}'].method,
		form: 'add_sub{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim};
</script>
{else}
<center><h2 style="color:#000">{$lang.fnrd}</h2></center>
{if $parent}
<center><span style="font-size:14px;">{$lang.frdv} {$parent->name}.</span></center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.fzk}</a></center>
<script language="javascript" type="application/javascript">
{literal}
hax('/filters/edit.html?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#catse").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
{/literal}
</script>
{/if}