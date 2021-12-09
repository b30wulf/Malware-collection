<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

unset($_SESSION['gsearch']);

if(!empty($_POST['uid'])){
	/*
	if(!empty($_POST['uid'])){		preg_match('~^([a-zA-Z]+)(.*)~', $_POST['uid'], $matches);
		if(!empty($matches[1])){			$prefix = $matches[1];
			$data = $matches[2];
		}else{			$prefix = 'unknown';
			$data = 'unknown';
		}
	}else{		$prefix = 'unknown';
		$data = 'unknown';
	}
	*/

	$pref = explode('0', $_POST['uid'], 2);

	if(count($pref) == 2){		$prefix = strtoupper($pref[0]);
		$data = strtoupper($pref[1]);
	}

	$filters_db = $mysqli->query('SHOW TABLE STATUS WHERE NAME LIKE \'bf\_filter\_%\'');
    $search = array();
	foreach($filters_db as $db_data){
		$result = $mysqli->query('select count(id) count from ' . $db_data->Name . ' where (prefix = \''.$prefix.'\') AND ((uid = \''.$data.'\') OR (uid = \'0'.$data.'\'))');
		//print_rm('select id, count(id) from ' . $db_data->Name . ' where (prefix = \''.$prefix.'\') AND (uid = \''.$data.'\')');
		if($result->count > 0){
			$search[str_replace('bf_filter_', '', $db_data->Name)] = str_replace('bf_filter_', '', $db_data->Name);
		}
	}
    $smarty->assign('search_count', count($search));

    if(count($search) > 0){
	    $smarty->assign('search', $search);

	    $_SESSION['gsearch']['prefix'] = $prefix;
	    $_SESSION['gsearch']['data'] = $data;
    }
}

?>