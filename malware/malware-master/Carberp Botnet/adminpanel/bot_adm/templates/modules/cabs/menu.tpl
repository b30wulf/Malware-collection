{if $_SESSION.user->access.cabs.bss eq on || $_SESSION.user->access.cabs.ibank eq on || $_SESSION.user->access.cabs.inist eq on || $_SESSION.user->access.cabs.cyberplat eq on || $_SESSION.user->access.cabs.screens eq on}
<div class="top_menu">

<div class="top_menu_left">

<a href="/cabs/index-1.html"{if $Cur.id eq '1'} style="color:red"{/if}>{$lang.bss}</a>
<a href="/cabs/index-2.html"{if $Cur.id eq '2'} style="color:red"{/if}>{$lang.ibank}</a>
<a href="/cabs/index-3.html"{if $Cur.id eq '3'} style="color:red"{/if}>{$lang.inist}</a>
<a href="/cabs/index-4.html"{if $Cur.id eq '4'} style="color:red"{/if}>{$lang.cp}</a>
<a href="/cabs/index-5.html"{if $Cur.id eq '5'} style="color:red"{/if}>{$lang.kp}</a>
<a href="/cabs/index-6.html"{if $Cur.id eq '6'} style="color:red"{/if}>{$lang.psb}</a>

{if $_SESSION.user->access.cabs.screens eq on}
{if file_exists("modules/cabs/screens.php")}<a href="/cabs/screens.html"{if $Cur.go eq 'screens'} style="color:red"{/if}>{$lang.screens}</a>&nbsp;{/if}
{/if}
</div>

<div class="top_menu_right" style="font-size:18px; color:#CCC">
{if $Cur.id eq '1'}
{$lang.bss}
{elseif $Cur.id eq '2'}
{$lang.ibank}
{elseif $Cur.id eq '3'}
{$lang.inist}
{elseif $Cur.id eq '4'}
{$lang.cp}
{elseif $Cur.id eq '5'}
{$lang.kp}
{elseif $Cur.id eq '6'}
{$lang.psb}
{elseif $Cur.go eq 'screens'}
{$lang.screens}
{/if}
</div>

</div>
<hr />
{/if}