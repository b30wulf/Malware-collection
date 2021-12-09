{if $Cur.page ne '0'}<a href="#" onclick="pp_{$rand_name}();">Предыдущая страница</a> {/if} {if $items|count eq 10}<a href="#" onclick="np_{$rand_name}();">Следующая страница</a>{/if}

<pre>
{foreach from=$items item=item name=logs}
{$item->data|data}
{/foreach}
</pre>

<div align="center"><br />
{if $Cur.page ne '0'}<a href="#" onclick="pp_{$rand_name}();">Предыдущая страница</a> {/if} {if $items|count eq 10}<a href="#" onclick="np_{$rand_name}();">Следующая страница</a>{/if}
</div><br />