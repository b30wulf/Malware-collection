<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="resourse-type" content="document" />
<meta name="document-state" content="dynamic" />
<title>{$title}</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" media="all" charset="utf-8" />
<link href="/css/{$Cur.to}.css" rel="stylesheet" type="text/css" />
<link href="/css/window.css" rel="stylesheet" type="text/css" />
{$javascript_begin}
<script type="text/javascript" src="/js/fullajax/fullajax.js"></script>
<script type="text/javascript" src="/js/core.js"></script>
<script type="text/javascript" src="/js/dragresize_commented.js"></script>
<script type="text/javascript" src="/js/window.js"></script>
{$javascript_end}
</head>

<body{if $body ne ''} {$body}{/if}>
<noscript><div id="noscript">У Вас отключен JavaScript!<br />Нормальная работа сайта без JavaScript невозможна! :(</div></noscript>
<div id="all">
<div id="logo">
<div id="account_top">
{if $smarty.session.user->PHPSESSID}
<br />Здравствуйте, <a href="/accounts/profile.html">{$smarty.session.user->login}</a>!<br /><br /><a href="/accounts/settings.html"><img src="/images/modules/accounts/usrcfg.png" alt="Настройки" /></a> <a href="/accounts/rights.html"><img src="/images/modules/accounts/usrrights.png" alt="Права доступа" /></a> <a href="/accounts/edit.html"><img src="/images/modules/accounts/usredit.png" alt="Редактирование" /></a> <a href="/accounts/exit.html">Выход</a>
{else}
<br />Здравствуйте, Гость!<br />Вы не авторизованны.<br /><a href="/accounts/authorization.html" onclick="/*window_open('autorize');*/">Вход</a>
{/if}
</div>
</div>
{if $smarty.session.user->PHPSESSID}
<div id="menu">
{if $smarty.session.user->access.clients.index eq on}<a href="/clients/">Клиенты</a>{/if}
{if $smarty.session.user->access.admins.index eq on}<a href="/admins/">Админки</a>{/if}
{if $smarty.session.user->access.files.index eq on}<a href="/files/">Файлы</a>{/if}
{if $smarty.session.user->access.logs.index eq on}<a href="/logs/">Логи</a>{/if}
{if $smarty.session.user->access.unnecessary.index eq on}<a href="/unnecessary/">Неиспользованные домены</a>{/if}
{if $smarty.session.user->access.threads.index eq on}<a href="/threads/">Потоки</a>{/if}
{if $smarty.session.user->access.accounts.index eq on}<a href="/accounts/">Пользователи</a>{/if}
<div id="menu_account" style="line-height:20px; vertical-align:middle">{$smarty.server.REMOTE_ADDR}&nbsp;</div>
</div>
<div id="position">{foreach from=$dir item=p name=dir}{$p}{if $p ne false}{if !$smarty.foreach.dir.last} -> {/if}{/if}{/foreach}
<div style="position:absolute; right: 5px; top: 0px;">

</div>
</div>
{/if}
<div id="content">{include file="$site_data"}<br /><br /></div>

<br />

<div id="banner">
<div id="banner_line" onmousemove="banner_mouse(true);" onmouseout="banner_mouse(false);">
<a href="http://validator.w3.org/check?uri=referer" target="_blank"><img src="/images/valid-xhtml10-blue.png" width="88" height="31" alt="Правильный XHTML 1.1" /></a>
<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank"><img src="/images/vcss-blue.gif" width="88" height="31" alt="Правильный CSS!" /></a>
<div>
<a href="http://www.opera.com/" target="_blank"><img src="/images/opera.gif" width="80" height="15" alt="Рекомендуемый браузер!" title="Рекомендуемый браузер!" /></a>&nbsp;
<a href="http://www.firefox.com/" target="_blank"><img src="/images/firefox.gif" width="80" height="15" alt="Рекомендуемый браузер!" title="Рекомендуемый браузер!" /></a>&nbsp;
</div>
</div>
</div>

<div id="footer"><div class="left"></div><div class="right">© 2009-{$smarty.now|date_format:"%Y"}</div></div>
<br /><br />
</div>

</body>
</html>
