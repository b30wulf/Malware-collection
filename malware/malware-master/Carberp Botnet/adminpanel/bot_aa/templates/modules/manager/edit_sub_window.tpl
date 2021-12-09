<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Изменение раздела {if $parent}<span style="font-size:10px;">(родитель:{$parent->name})</span>{/if}';
</script>
{if $save eq ''}
<form action="/manager/edit_sub-{$Cur.id}.html?window=1" name="add_sub{$rand_name}" id="add_sub{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
{if $errors ne ""}
<div align="center" style="padding-top: 10px; padding-bottom: 10px">
{$errors}
</div>
{/if}
<div style="width: 500px; padding-left: 16px">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{if $parent}
<tr class="bgp2">
    <th style="text-align: left; width: 130px;">Родитель</th>
    <th style="text-align: left;">{$parent->name}</th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
{/if}
<tr class="bgp2">
    <th style="text-align: left;">Текущее название</th>
    <th style="text-align: left;">{$item->name}</th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Новое название</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="submit" value="Добавить" class="user" /></th>
</tr>
</table>
</div>
</form>
<script language="javascript" type="application/javascript">
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
<center><h2>Раздел изменен!</h2></center>
{if $parent}
<center><span style="font-size:14px;">Раздел изменен!<br />(Родитель: {$parent->name}).</span></center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">Закрыть окно</a></center>
<script language="javascript" type="application/javascript">
{literal}
hax('/manager/?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
{/literal}
</script>
{/if}