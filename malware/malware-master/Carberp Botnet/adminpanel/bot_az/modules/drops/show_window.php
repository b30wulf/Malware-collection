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

if(!empty($Cur['id'])){
	$item = $mysqli->query('SELECT * from bf_drops WHERE id = \''.$Cur['id'].'\' LIMIT 1');
	
	if($item->id == $Cur['id']){
		$smarty->assign('drop', $item);
		
		if($_SESSION['user']->config['infoacc'] == '1'){
			$sql = array();
			$sql['sys'] = '';
			
			foreach($_SESSION['user']->config['systems'] as $key => $item){
			    $sql['sys'] .= ' OR (nid = \''.$key.'\')';
			}
			
			foreach($sql as $sk => $si){
				$sql[$sk] = preg_replace('~^ OR ~', '', $sql[$sk]);
				$sql[$sk] = '('.$sql[$sk].') AND ';
			}
			
			$mysqli->query('SELECT * from bf_systems WHERE ' . $sql['sys'], null, 'system_get');
		}else{
			$mysqli->query('SELECT * from bf_systems', null, 'system_get');
		}
		
		$smarty->assign('systems', $systems);
		$item->system = explode('|', $item->system);
		
		if(!empty($_SESSION['user']->config['userid'])){
			$transfers = $mysqli->query('SELECT * from bf_transfers WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (`drop_id` = \''.$item->id.'\') ORDER by id DESC', null, null, false);
		}else{
			$transfers = $mysqli->query('SELECT * from bf_transfers WHERE (`drop_id` = \''.$item->id.'\') ORDER by id DESC', null, null, false);
		}
		
		$smarty->assign('transfers', $transfers);
		
		/*
		if(count($item->system) > 1){
			$sql = '';
			foreach($item->system as $sys){
			if(!empty($sys)) $sql .= 'OR (system = \''.$sys.'\') ';
			}
			if(!empty($sql)){
				$sql = ltrim($sql, 'OR ');
				if(strpos($sql, 'OR ') !== false){
					//$transfers = $mysqli->query('SELECT * from bf_transfers WHERE (`drop_id` = \''.$item->id.'\') AND (' . $sql . ') ORDER by id DESC', null, null, false);
					$transfers = $mysqli->query('SELECT * from bf_transfers WHERE (`drop_id` = \''.$item->id.'\') ORDER by id DESC', null, null, false);
				}else{
					//$transfers = $mysqli->query('SELECT * from bf_transfers WHERE (`drop_id` = \''.$item->id.'\') AND (' . $sql . ') ORDER by id DESC', null, null, false);
					$transfers = $mysqli->query('SELECT * from bf_transfers WHERE (`drop_id` = \''.$item->id.'\') ORDER by id DESC', null, null, false);
				}
				
				$smarty->assign('transfers', $transfers);
			}
		}
		*/
	}
}

?>