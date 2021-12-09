{if $save eq ''}
<form action="{if $parent}/manager/add_sub-{$Cur.id}.html?window=1{else}/manager/add_sub.html?window=1{/if}" name="add_sub{$rand_name}" id="add_sub{$rand_name}" enctype="application/x-www-form-urlencoded" method="post" onsubmit="submit_{$rand_name}(); return false;">
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
    <th style="text-align: left; width: 80px;">В раздел</th>
    <th style="text-align: left;">{$parent->name}</th>
</tr>
{/if}
<tr class="bgp2">
    <th style="text-align: left; width: 80px;">Название</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="button" value="Добавить" class="user" onclick="submit_{$rand_name}();" /></th>
</tr>
</table>
</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{if $parent}Добавления подраздела{else}Добавления раздела{/if}';
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
<center><h2 style="color:#000">Новый раздел добавлен!</h2></center>
{if $parent}
<center><span style="font-size:14px;">Раздел добавлен в {$parent->name}.</span></center>
{/if}
<br />
<center><a href="#" onclick="window_close_opacity(this.parentNode.parentNode.parentNode.id, 1);">Закрыть окно</a></center>
<script language="javascript" type="application/javascript">
{literal}
hax('/manager/?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
{/literal}
</script>
{/if}