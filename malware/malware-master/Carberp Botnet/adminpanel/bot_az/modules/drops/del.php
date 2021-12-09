<?php

if(!empty($Cur['id'])){
	if(!empty($_SESSION['user']->config['userid'])){
		$item = $mysqli->query('SELECT * from bf_drops WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (id = '.$Cur['id'].') LIMIT 1');
	}else{
		$item = $mysqli->query('SELECT * from bf_drops WHERE (id = '.$Cur['id'].') LIMIT 1');
	}
	/*
	print_rm($item);
	if($item->id == $Cur['id'] && $_SESSION['user']->config['infoacc'] == '1'){
		$systems = explode('|', $item->system);
		$old_id = $item->id;
		$item->id = mt_rand();
		
		foreach($systems as $sys){
			if($_SESSION['user']->config['systems'][$sys] == true){
				$item->id = $old_id;
				break;
			}
		}
	}
	*/
	if($item->id == $Cur['id']){
		if(!empty($_SESSION['user']->config['userid'])){
			$mysqli->query('DELETE FROM bf_drops WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (id = \''.$item->id.'\') LIMIT 1');
		}else{
			$mysqli->query('DELETE FROM bf_drops WHERE (id = \''.$item->id.'\') LIMIT 1');
		}
	}
}

header('Location: /drops/index.html');
exit;

?>