{if $save eq ''}
<form action="" enctype="application/x-www-form-urlencoded" method="post">
<h2>Добавления раздела {if $parent}<span style="font-size:14px;">(в раздел {$parent->name})</span>{/if}</h2>
{if $errors ne ""}
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
    <th style="text-align: left; width: 250px;">Название</th>
    <th style="text-align: left;"><input name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
  <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2"><input name="submit" type="submit" value="Добавить" class="user" /></th>
</tr>
</table>
</form>
{else}
<center><h2>Новый раздел добавлен!</h2></center>
{if $parent}
<center><span style="font-size:14px;">Раздел добавлен в {$parent->name}.</span></center>
{/if}
<br />
<center><a href="/catalog/">Перейти в каталог фильтров</a></center>
{/if}