{foreach from=$logs item=l1 key=k1}
<div id="d{$k1}">
<h2 style="text-align:center; font-size: 22px">{$dt[$k1]}</h2>

<div style="font-size:10px; position:relative" align="center">
<div style="position:absolute; left: 0px; top:10px">{$lang.maxstr}: {$_SESSION.user->config.cp.logs}</div>
&nbsp;{$pages[$k1]}&nbsp;<br /><br />
<div style="position:absolute; right: 0px; top:10px">{$lang.vsego}: {$counts[$k1]}</div>
</div>

{foreach from=$l1 item=l2 key=k2 name=k2}
{if $smarty.foreach.k2.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<table border="0" cellspacing="1" cellpadding="5" class="t" style="width: 100%; text-align:center; border: 1px solid #cccccc;">
  <tr class="bg1">
    <td style="width:200px">{$lang.bot}</td>
    <td {if $online[$k2]}style="background-color:#0F0"{/if}><a onclick="get_bot_window('{$k2}');" style="cursor:pointer">{$k2}</a></td>
  </tr>
  <tr class="bgp{$bg}">
    <td colspan="2" style="cursor:pointer; padding-left: 20px; padding-right:20px" onclick="gbw('{$k2}', '{$k1}', this);"><a href="#">{$lang.show}</a> ({foreach from=$l2.type item=l3}&nbsp;{$type[{$l3}]}{/foreach} )</td>
    </tr>
</table>

<br />

{/foreach}
<hr style="border: 1px solid #666" />
</div>
{/foreach}