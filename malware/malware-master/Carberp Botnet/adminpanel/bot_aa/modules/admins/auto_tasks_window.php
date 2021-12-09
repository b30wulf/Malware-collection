<?php

if(!empty($Cur['id'])){
	$mysqli->query('delete from bf_cmds where (id = \''.$Cur['id'].'\')');
}elseif($Cur['str'] == 'ALL'){	$mysqli->query('delete from bf_cmds');
}

$cmds = $mysqli->query("SELECT a.id, a.cmd, b.link FROM bf_cmds a, bf_admins b WHERE (b.id = a.post_id)", null, null, false);

$smarty->assign('cmds', $cmds);

?>