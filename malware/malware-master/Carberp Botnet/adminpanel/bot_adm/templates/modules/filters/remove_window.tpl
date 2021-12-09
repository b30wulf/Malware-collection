{if $save eq true}
<form action="#" method="post" name="remove{$rand_name}" id="remove{$rand_name}">
<input type="hidden" />
</form>
<script language="javascript" type="application/javascript">
window_close_opacity(document.forms['remove{$rand_name}'].parentNode.parentNode.id, 1);
{literal}
hax('/filters/edit.html?ajax=1',{id: 'cats_content',nohistory:true,nocache:true,destroy:true,onload: function (){$("#catse").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})
{/literal}
</script>
{else}
<div align="center" style="text-align:center; width: 750px;">
{if $parent->host eq ''}
<h2><span style="color: #F00">{$lang.warn}</span><br />{$lang.fvxyr}</h2>
<br />
<div style="font-size: 16px;">
<div>{$lang.fifrbpy}</div><br />
<div>{$lang.fpyryvrf}</div><br />
<div>{$lang.fervperf}</div><br />
<div>{$lang.fpyrnbvdf}</div><br />
</div>

<h2>{$lang.ftyr}</h2>
(<span style="font-size:14px">{$parent->name}</span>)
<br /><br />
<form action="/filters/remove-{$parent->id}.html?window=1" method="post" name="remove{$rand_name}" id="remove{$rand_name}">
<input type="button" name="yes" value="{$lang.fdy}" onclick="submit_{$rand_name}();"/>
<input type="button" name="no" value="{$lang.fny}" onclick="nosubmit_{$rand_name}();" />
</form>
{else}
<h2><span style="color: #F00">{$lang.warn}</span><br />{$lang.fvxyf}</h2>
<br />
<div style="font-size: 16px;">
<div>{$lang.fsfvibyv}</div><br />
</div>

<h2>{$lang.ftyf}</h2>
(<span style="font-size:14px">{$parent->name}</span>)
<br /><br />
<form action="/filters/remove-{$parent->id}.html?window=1" method="post" name="remove{$rand_name}" id="remove{$rand_name}">
<input type="button" name="yes" value="{$lang.fdy}" onclick="submit_{$rand_name}();"/>
<input type="button" name="no" value="{$lang.fny}" onclick="nosubmit_{$rand_name}();" />
</form>
{/if}
</div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['remove{$rand_name}'].parentNode.parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{if $parent->host ne ''}{$lang.fyf}{else}{$lang.fyr}{/if}';
function submit_{$rand_name}(){ldelim}
hax(document.forms['remove{$rand_name}'].action,
	{ldelim}
		method: document.forms['remove{$rand_name}'].method,
		form: 'remove{$rand_name}',
		id: id_{$rand_name} + '_content',
		nohistory:true,
		nocache:true,
		destroy:true,
		rc:true
	{rdelim}
)
{rdelim};
function nosubmit_{$rand_name}(){ldelim}
window_close_opacity(document.forms['remove{$rand_name}'].parentNode.parentNode.parentNode.id, 1);
{rdelim};
</script>
{/if}