<div class="top_menu">

<div class="top_menu_left"><a href="/bots/index.html">{$lang.nksc}</a> <a href="/bots/config.html?x=rehash">{$lang.rehash}</a></div>
<div class="top_menu_right">
<form action="/bots/config.html" method="post" enctype="multipart/form-data">
{$lang.file}: <input type="file" id="file" name="file" />
<input type="submit" name="update" value="{$lang.add}" />
</form>
</div>

</div>
<hr />
{if $upload_false eq true}<div style="text-align:center; color:#F00;font-size:14px">{$lang.fnbz}</div>
<hr />{/if}
{if $files|@count > 0}
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp3">
	<td style="width: 50%; text-align: center">{$lang.nazvfi}</td>
	<td style="width: 20%; text-align: center">{$lang.razmer}</td>
	<td style="width: 30%; text-align: center">{$lang.datasoz}</td>
	<td></td>
</tr>
{foreach from=$files item=file name=users}
{if $smarty.foreach.users.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}" onmousemove="this.className = 'bgp4'" onmouseout="this.className = 'bgp{$bg}'" style="border: 1px solid #FFFFFF">
	<th><a href="{$file.link}" target="_blank">{$file.name}</a></th>
	<th>{$file.size|size_format}</th>
	<th>{$file.date|TimeStampToStr}</th>
	<th>{if !$file.name|check_del}<a href="#" onclick="delete_cf('{$file.name|remove_thk}');"><img src="/images/delete.png" alt="Удаление" /></a>{else}&nbsp;{/if}</th>
</tr>
{/foreach}
</table>
{else}
<h4 align="center">{$lang.nichenn}</h4>
{/if}
<hr />