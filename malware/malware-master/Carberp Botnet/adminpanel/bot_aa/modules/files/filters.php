<?php

if($Cur['type'] == 1){
	$dir = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir_site = realpath($dir . '/../../');
	system('cd ' . $dir_site . '/crons/; ./filters_run.php &> /dev/null &');
}

if(!empty($Cur['id'])){
	$mysqli->query("delete from bf_filters_task WHERE (id='".$Cur['id']."')");
	$mysqli->query("delete from bf_filters_result WHERE (sid='".$Cur['id']."')");
    $dir = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir_site = realpath($dir . '/../../');
	@unlink($dir_site . '/cache/filters/' . $Cur['id'] . '.txt');
}

if(isset($_POST['add'])){
	@array_walk($_POST, 'real_escape_string');

	if($_POST['data'] != '*'){		if(($_POST['data'][0] . $_POST['data'][1] . $_POST['data'][2]) == ($_POST['data'][3] . $_POST['data'][4] . $_POST['data'][5])){
			$_POST['data'] = $_POST['data'][0] . '.' . $_POST['data'][1] . '.' . $_POST['data'][2];
		}else{			$_POST['data'] = $_POST['data'][0] . '.' . $_POST['data'][1] . '.' . $_POST['data'][2] . '-' . $_POST['data'][3] . '.' . $_POST['data'][4] . '.' . $_POST['data'][5];
		}
	}

	$mysqli->query("INSERT INTO bf_filters_task (prefix, date, sparam) VALUES ('".$_POST['prefix']."', '".$_POST['data']."', '".$_POST['type']."')");
}

$smarty->assign("prefix", $prefix);
$smarty->assign("list", $mysqli->query('SELECT * FROM bf_filters_task ORDER by id DESC', null, null, false));

?>