<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
</script>
<script language="javascript" type="application/javascript">
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.scotdb} #{$Cur.str}';

function sC{$rand_name}(){ldelim}
	document.getElementById('upload{$rand_name}').style.display = 'none';
	document.getElementById('loader{$rand_name}').style.display = 'block';
{rdelim}
 
function fC{$rand_name}(text){ldelim}
	document.getElementById('upload{$rand_name}').style.display = 'block';
	document.getElementById('loader{$rand_name}').style.display = 'none';
	document.getElementById('file{$rand_name}').value = '';
	if(text == 'error'){ldelim}
		alert('{$lang.errload}');
	{rdelim}else if(text == 'ext_not'){ldelim}
		alert('{$lang.dtfnp}');
	{rdelim}else{ldelim}
		document.getElementById('images{$rand_name}').innerHTML = document.getElementById('images{$rand_name}').innerHTML + '<div style="border: 1px solid #000">'+text+'</div><br />';
		tb_init('a.thickbox, area.thickbox, input.thickbox');
		//document.getElementById('response').innerHTML = text;
	{rdelim}
{rdelim}

tb_init('a.thickbox, area.thickbox');
</script>
<br />
<div align="center" id="loader{$rand_name}" style="display:none"><img src="/images/loadingAnimation.gif" /></div>
<div align="center" id="upload{$rand_name}">

<form id="form{$rand_name}" action="/cabs/rscreens-{$Cur.str}.html?window=1" method="post" enctype="multipart/form-data" onsubmit="new SRAX.Uploader(this, sC{$rand_name}, fC{$rand_name})">

{$lang.zagk}: <input type="file" name="file" id="file{$rand_name}" />

<input type="submit" name="submit" value="{$lang.add}" />
</form>
</div>
<br />

<div id="images{$rand_name}">
{foreach from=$items item=item}
<div style="border: 1px solid #000">
<a href="/cache/rscreens/{$Cur.str}/{$item}" class="thickbox">{$item}</a>
</div><br />
{/foreach}
</div>
<br />