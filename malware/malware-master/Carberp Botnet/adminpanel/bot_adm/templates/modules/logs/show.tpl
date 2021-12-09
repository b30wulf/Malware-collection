{foreach from=$logs item=log name=logs}

<table border="0" cellspacing="1" cellpadding="5" class="t" style="width: 100%; text-align:center; border: 1px solid #cccccc; font-size:10px">
  <tr class="bg1">
    <td style="width: 220px">{$lang.type} / {$lang.brw} / {$lang.protocol} / {$lang.ip} / {$lang.country}</td>
    <td>{$type[{$log->type}]} / {$log->brw} / {$log->protocol} / {$log->ip} / {$log->country}</td>
  </tr>
  <tr class="bg2">
    <td>{$lang.url} ({$log->post_date})</td>
    <td>{$log->url}</td>
  </tr>
  {if $log->data ne ''}
  <tr class="bg1">
    <td>{$lang.data}</td>
    <td><textarea class="data">{$log->data}</textarea></td>
  </tr>
  {/if}
</table>

<hr style="border: 1px solid #000" />

{/foreach}