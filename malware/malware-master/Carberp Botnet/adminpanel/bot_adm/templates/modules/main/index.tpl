{if $_SESSION.user->access.main.info eq on || $_SESSION.user->access.main.edit eq on || $_SESSION.user->access.main.stat eq on}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.main.info eq on}<a href="/main/info.html">{$lang.info}</a>&nbsp;{/if}
{if $_SESSION.user->access.main.stat eq on}<a href="/main/stat.html">{$lang.stat}</a>&nbsp;{/if}
</div>
</div>
{/if}
{$html}