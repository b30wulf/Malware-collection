<h2>Информация о пользователе {$user->login|ucfirst}</h2>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">Емаил</th>
    <th style="text-align: left;">{$user->email}</th>
</tr>

<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Статус</th>
    <th style="text-align: left;">{if $user->PHPSESSID ne ''}Онлайн{else}Оффлайн{/if}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Авторизация</th>
    <th style="text-align: left;">{if $user->enable eq '1'}Разрешено{if $smarty.session.user->access.accounts.enable_disable eq 'on'} <input type="button" value="Запретить" onclick="location.href = '/accounts/profile-{$user->id}.html?type=0'" />{/if}{else}Запрещено{if $smarty.session.user->access.accounts.enable_disable eq 'on'} <input type="button" value="Разрешить" onclick="location.href = '/accounts/profile-{$user->id}.html?type=1'" />{/if}{/if}</th>
</tr>
{if $user->config.check.icq ne ''}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Код подтверждения ICQ:</th>
    <th style="text-align: left;">{$user->config.check.icq}</th>
</tr>
{/if}
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Последняя активность</th>
    <th style="text-align: left;">{$user->expiry_date}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">Авторизация</th>
    <th style="text-align: left;">{$user->enter_date}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Зарегистрирован</th>
    <th style="text-align: left;">{$user->post_date}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">Изменение профиля</th>
    <th style="text-align: left;">{$user->update_date}</th>
</tr>
<tr class="bgp2">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">Экран</th>
    <th style="text-align: left;">{if $user->info->screen->w}{$user->info->screen->w}x{$user->info->screen->h} ({$user->info->screen->c}){/if}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">Платформа</th>
    <th style="text-align: left;">{if $user->info->platform}{$user->info->platform|urldecode}{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">Локализация</th>
    <th style="text-align: left;">{if $user->info->language}{$user->info->language|urldecode}{/if}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">IP</th>
    <th style="text-align: left;">{if $user->info->REMOTE_ADDR}{$user->info->REMOTE_ADDR}{if $user->info->REMOTE_PORT}:{$user->info->REMOTE_PORT}{/if}{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">Браузер</th>
    <th style="text-align: left;">{$user->info->HTTP_USER_AGENT}</th>
</tr>
</table>
<hr />