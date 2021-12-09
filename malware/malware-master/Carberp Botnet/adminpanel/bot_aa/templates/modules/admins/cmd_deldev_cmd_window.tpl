<form action="#" enctype="application/x-www-form-urlencoded" method="post" name="add_sub{$rand_name}" id="add_sub{$rand_name}" onsubmit="return false;">
<br />
<div align="center">Всего скрытых заданий в админке: {$cmds}</div>
</form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['add_sub{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = 'Удаление всех скрытых комманд  - {$admin->link}';

</script>