<form action="" enctype="application/x-www-form-urlencoded" method="post" onsubmit="this.elements['info'].value = save_info();">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">{$lang.name}</th>
    <th style="text-align: left;"><input id="name" name="name" type="text" value="{$smarty.post.name}" class="user" /></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.prefixs}</th>
    <th style="text-align: left;"><input id="prefixs" name="prefixs" type="text" value="{$smarty.post.prefixs}" class="user" /></th>
</tr>
<tr class="bgp2">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
    <th colspan="2"><input id="edit_submit" name="edit_submit" type="submit" value="{$lang.edit}" class="user" /></th>
</tr>
</table>
</form>