<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.fif} {if $parent}<span style="font-size:10px;">({$lang.fro}:{$parent->name})</span>{/if}';

function submit_{$rand_name}(){ldelim}
get_hax(
	{ldelim}
		url:document.forms['add_filter{$rand_name}'].action,
		method: document.forms['add_filter{$rand_name}'].method,
		form: 'add_filter{$rand_name}',
		id: id_{$rand_name} + '_content',
	{rdelim}
);
{rdelim}

</script>
{if $save eq ''}
<form action="/filters/edit_filter-{$Cur.id}.html?window=1" name="add_filter{$rand_name}" id="add_filter{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
<input name="fields" type="hidden" value="{$smarty.post.fields}" />
{if $errors ne ""}
<div align="center" style="padding-top: 10px; padding-bottom: 10px">{$errors}</div>
{/if}
<div style="width: 900px; padding-left: 16px">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">{$lang.fn}</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">{$lang.fsd}</th>
    <th style="text-align: left;"><input name="host" type="text" value="{$smarty.post.host}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<!--
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">{$lang.fslif}</th>
    <th style="text-align: left;"><input name="savelog" type="checkbox"{if $smarty.post.savelog eq 1} checked="checked"{/if} /></th>
</tr>
-->
<tr class="bgp2">
  <th colspan="2"><input name="submit" type="button" value="{$lang.add}" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
</div>
</form>
{else}
<center><h2>{$lang.fiz}</h2></center>
{if $parent}
<center><span style="font-size:14px;">{$lang.fiz}<br />({$lang.fro}: {$parent->name}).</span></center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">{$lang.fzk}</a></center>
<script language="javascript" type="application/javascript">
{literal}
hax('/filters/edit.html?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#catse").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
{/literal}
</script>
{/if}