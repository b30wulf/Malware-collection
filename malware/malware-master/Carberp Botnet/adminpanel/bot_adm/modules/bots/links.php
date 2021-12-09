<?php

if(isset($_POST['submit']) && !empty($_POST['link'])){
	$mysqli->query('INSERT INTO bf_links (link) VALUES (\''.$_POST['link'].'\')');
}

if(!empty($Cur['id'])){	$link = $mysqli->query('SELECT * FROM bf_links WHERE (id = \''.$Cur['id'].'\') AND (dev = \'0\') LIMIT 1');
	if($link->id === $Cur['id']){		$mysqli->query('delete from bf_links WHERE (id = \''.$link->id.'\') LIMIT 1');
	}
}

$links = $mysqli->query('SELECT * FROM bf_links WHERE (dev = \'0\')', null, null, false);

$smarty->assign('links', $links);

?>