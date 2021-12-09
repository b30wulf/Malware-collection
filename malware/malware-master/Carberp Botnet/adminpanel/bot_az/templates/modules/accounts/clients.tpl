<div class="top_menu">

<div class="top_menu_left">
{if $_SESSION.user->access.accounts.clients_add eq on}<a href="#null" onclick="cleint_add();">Добавить клиента</a>{/if}
</div>

<div class="top_menu_right"></div>

</div>
<hr />

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="width: 20%; text-align: center">{$lang.login}</td>
    <td style="width: 40%; text-align: center">{$lang.prefixss}</td>
	<td style="width: 20%; text-align: center">{$lang.userid}</td>
    <td style="width: 20%; text-align: center">{$lang.countuser}</td>
    <td style="width: 1px;"></td>
</tr>
{foreach from=$clist item=cl key=cid name=users}
{if $smarty.foreach.users.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF">
	<th>{$cl|ucfirst}</th>
	<th>{$cid|getpref}</th>
    <th>{$cid}</th>
    <th>{$cid|count_user}</th>
    <th><a href="/accounts/clients_edit.html?str={$cid}"><img src="/images/edit.png" alt="{$lang.edit}" /></a></th>
</tr>
{/foreach}
</table>
