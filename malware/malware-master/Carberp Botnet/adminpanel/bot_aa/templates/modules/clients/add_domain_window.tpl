<form action="/clients/add_domain.html?window=1&id={$Cur.id}&y={$Cur.y}&x={$Cur.x}" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="submit_{$rand_name}(); return false;">
  {if $account_errors ne ""}
  <div align="center">{$account_errors}</div><br />
  {/if}
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
    <tr class="bgp1">
      <th style="text-align: left; width: 150px;">Сервер</th>
      <th style="text-align: left;">
      <select name="server" style="width:100%" class="user">
      {if $use_server ne ''}
      <option value="{$use_server}" selected="selected">{$use_server}</option>
      {else}
      <option selected="selected">Неизвестно, узнать автоматически</option>
      {foreach from=$clients item=c}<optgroup label="{$c->name}">{foreach from=$c->servers item=server}<option value="{$server->ip}">{$server->ip}</option>{/foreach}</optgroup>{/foreach}
      {/if}
      </select>
      </th>
    </tr>
    <tr class="bgp2">
      <th style="text-align: left; width: 150px;">Домен/ы<br /><br /><span style="font-size:10px">Каждая строка<br />отдельный домен</span></th>
      <th style="text-align: left;"><textarea name="domains" rows="5" cols="20" class="user"></textarea></th>
    </tr>
    <tr class="bgp1">
      <th colspan="2"><input name="submit" type="button" value="Добавить" class="user" onclick="submit_{$rand_name}();" /></th>
    </tr>
  </table>
</form>

<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Добавления доменов{if $client->name ne ''} для "{$client->name}"{/if}';

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