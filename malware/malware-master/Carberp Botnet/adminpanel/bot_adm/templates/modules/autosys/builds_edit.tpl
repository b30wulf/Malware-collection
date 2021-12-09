<hr />
<h2 align="center">{$lang.edit_builds}</h2><hr />
{if $errors ne ""}
<div align="center">{$errors}</div><hr />
{/if}

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg2">
	<th style="text-align: left;">ID</th>
	<th style="text-align: left;">{$item->id}</th>
</tr>
<tr class="bg1" >
	<th style="text-align: left;">MD5</th>
	<th style="text-align: left;">{$item->md5}</th>
</tr>
</table>
<hr />
<form action="/autosys/builds_edit-{$item->id}.html" enctype="multipart/form-data" method="post">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{if $item->link eq ''}
<tr class="bg2">
    <th style="text-align: left;"><input name="file" id="file" type="file" />
   </th>
</tr>
{else}
<tr class="bg2">
    <th style="text-align: left;"><input name="link" id="link" type="text" style="width:100%" value="{$item->link}" />
   </th>
</tr>
{/if}
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2" style="text-align: left;"><input type="submit" name="submit" class="user" style="width:100%" value="{$lang.add}" /></th>
</tr>
</table>
</form>