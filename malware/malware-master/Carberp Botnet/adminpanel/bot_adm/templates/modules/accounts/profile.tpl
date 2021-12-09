<h2 align="center">{$lang.ainfo} #{$user->id}</h2>
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp2">
    <th style="text-align: left; width: 250px;">{$lang.login}</th>
    <th style="text-align: left;">{$user->login|ucfirst}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.status}</th>
    <th style="text-align: left;">{if $user->PHPSESSID ne ''}{$lang.online}{else}{$lang.offline}{/if}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.autord}</th>
    <th style="text-align: left;">{if $user->enable eq '1'}{$lang.ara}{if $_SESSION.user->access.accounts.enableanddisable eq 'on'}
    <input type="button" value="{$lang.aza1}" onclick="location.href = '/accounts/profile-{$user->id}.html?type=0'" />{/if}{else}{$lang.aza}{if $_SESSION.user->access.accounts.enableanddisable eq 'on'} <input type="button" value="{$lang.ara1}" onclick="location.href = '/accounts/profile-{$user->id}.html?type=1'" />{/if}{/if}</th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.apa}</th>
    <th style="text-align: left;">{$user->expiry_date}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.autord}</th>
    <th style="text-align: left;">{$user->enter_date}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.azareg}</th>
    <th style="text-align: left;">{$user->post_date}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.aedp}</th>
    <th style="text-align: left;">{$user->update_date}</th>
</tr>
<tr class="bgp2">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.aekr}</th>
    <th style="text-align: left;">{if $user->info->screen->w}{$user->info->screen->w}x{$user->info->screen->h} ({$user->info->screen->c}){/if}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.os}</th>
    <th style="text-align: left;">{if $user->info->platform}{$user->info->platform|urldecode}{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.localize}</th>
    <th style="text-align: left;">{if $user->info->language}{$user->info->language|urldecode}{/if}</th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;">{$lang.ip}</th>
    <th style="text-align: left;">{if $user->info->REMOTE_ADDR}{$user->info->REMOTE_ADDR}{if $user->info->REMOTE_PORT}:{$user->info->REMOTE_PORT}{/if}{/if}</th>
</tr>
<tr class="bgp1">
    <th style="text-align: left;">{$lang.brouz}</th>
    <th style="text-align: left;">{$user->info->HTTP_USER_AGENT}</th>
</tr>
</table>
<hr />