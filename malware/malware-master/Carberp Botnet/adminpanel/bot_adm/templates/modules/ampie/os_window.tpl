<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="text/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.gos}';
</script>

<embed src="/images/ampie/ampie.swf?data_file=/ampie/os.html?ajax=1&settings_file=/images/ampie/os.xml&path=/images/ampie/" style="width:100%;height:100%"></embed>