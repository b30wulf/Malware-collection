{if $registration_end ne true}
<form action="/clients/add.html?window=1" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
  <h2 align="center">Добавления клиента</h2>
  {if $account_errors ne ""}
  <div align="center">{$account_errors}</div><br />
  {/if}
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
    <tr class="bgp2">
      <th style="text-align: left; width: 150px;">Имя клиента</th>
      <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp1">
      <th colspan="2"><input name="submit" type="button" value="Добавить" class="user" onclick="submit_{$rand_name}();" /></th>
    </tr>
  </table>
</form>
{else}
<script language="javascript" type="application/javascript">
get_hax({ldelim}url: '/clients/index.html?ajax=1'{rdelim});
</script>
<br />
<h2 align="center">Клиент добавлен!</h2>
<hr />
{/if}

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Добавления клиента';

function submit_{$rand_name}(){ldelim}
hax(document.forms['add_sub{$rand_name}'].action,
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
{rdelim};
</script> 