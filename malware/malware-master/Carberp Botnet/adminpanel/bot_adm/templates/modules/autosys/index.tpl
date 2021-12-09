{if $_SESSION.user->access.autosys.index eq on}
<div class="top_menu">
<div class="top_menu_left">
{if $_SESSION.user->access.autosys.domains eq on}<a href="/autosys/domains.html">{$lang.domains}</a>&nbsp;{/if}
{if $_SESSION.user->access.autosys.builds eq on}<a href="/autosys/builds.html">{$lang.builds}</a>&nbsp;{/if}
</div>

<div class="top_menu_right">

</div>

</div>
<hr />
{/if}