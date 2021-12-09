<form action="" name="rights" method="post" enctype="multipart/form-data" target="_self">

<div align="center"><h2>{$lang.apd}: {$user->login} <input name="all_{$r_key}" type="button" value="±" onclick="right('', 'rights')" style="font-size: 10px; height: 18px; line-height: 16px" /></h2></div>

<br />

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{foreach from=$rights item=r_value key=r_key name="r"}
<tr>
<th class="bgp3" style="width:100%; text-align:center">{$lang["{$r_key}_index"]}</th>
<th class="bgp3" style="text-align:center"><input name="all_{$r_key}" type="button" value="±" onclick="right('{$r_key}', 'rights')" style="font-size: 10px; height: 18px;"  /></th>
</tr>
{foreach from=$r_value item=i_value key=i_key name="i"}
{if $smarty.foreach.i.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr style="cursor:pointer;" class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" onclick="check(document.forms['rights'].elements['rights[{$r_key}][{$i_key}]'])">
		<th style="width:100%; text-align:left;">{if $i_key eq 'index'}{$lang.agd}:{else}{$lang.ad}:{/if} {$lang["{$r_key}_{$i_key}"]}</th>
		<th valign="middle"><input name="rights[{$r_key}][{$i_key}]" type="checkbox" {if $user->access.$r_key.$i_key eq 'on'}checked="checked"{/if} onclick="check(this)" /></th>
</tr>
{/foreach}
{/foreach}
</table>
<br /><br />
<input type="submit" name="save" value="{$lang.save}" style="width:100%" />

</form>