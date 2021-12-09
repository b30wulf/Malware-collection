{if $registration_end ne true}
<form action="" enctype="application/x-www-form-urlencoded" method="post">
  <h2>Добавления админки</h2>
  {if $account_errors ne ""}
  <div align="center">{$account_errors}</div><br />
  {/if}
  <table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
    <tr class="bgp2">
      <th style="text-align: left; width: 150px;">Домен админки</th>
      <th style="text-align: left;"><input name="link" type="text" value="{$smarty.post.link}" class="user" style="width:100%" /></th>
    </tr>
    <tr class="bgp1">
      <th colspan="2"><input name="submit" type="submit" value="Добавить" class="user" /></th>
    </tr>
  </table>
</form>
{else}
<br />
<h2>Админка добавлена!</h2>
<hr />
{/if}