{if $registration_end ne true}
<form action="/clients/add_server-{$Cur.id}.html?window=1" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
  <h2 align="center">Добавления сервера для "{$client->name}"</h2>
  {if $account_errors ne ""}
  <div align="center">{$account_errors}</div><br />
  {/if}
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
    <tr class="bgp1">
      <th style="text-align: left; width: 150px;">IP сервера * </th>
      <th style="text-align: left;"><input name="ip" type="text" value="{$smarty.post.ip}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left; width: 150px;">Ключ админки</th>
      <th style="text-align: left;"><input name="key" type="text" value="{$smarty.post.key}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp1">
      <th style="text-align: left; width: 150px;">Шелл</th>
      <th style="text-align: left;">
      <select name="shell" style="width:100%" class="user">
      <option value="/set/task.html" selected="selected">/set/task.html - Отстук бота</option>
      <option value="/index.php">/index.php - Главный файл админки</option>
      <option value="/set/cmd.html">/set/cmd.html - Чистый шелл</option>
      <option value="/get/cab.html">/get/cab.html - Отстук на каб файлы</option>
      <option value="/get/gra.html">/get/gra.html - Граббер логи</option>
      <option value="/get/scr.html">/get/scr.html - Скрины</option>
      <option value="/get/sni.html">/get/sni.html - Снифер логи</option>
      </select>
      </th>
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
<h2 align="center">Сервер для "{$client->name}" добавлен!</h2>
<hr />
{/if}

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Добавления сервера';

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