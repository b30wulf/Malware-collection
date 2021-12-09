<?php

get_function('rc');

if(isset($_POST['load_ddl'])){
    $new_file = realpath('s.dll');
    if(empty($new_file)){
	$new_file = realpath('./');
	$new_file .= $new_file . 's.dll';
    }
    
    if(move_uploaded_file($_FILES['new_dll']['tmp_name'], $new_file)){
	header('Location: /settings/');
	exit;
    }else{
	header("Status: 500");
	header("HTTP/1.1 500");
	exit;
    }
}

if(isset($_POST['save'])){
    unset($_POST['save']);

    $conf = json_decode(file_get_contents('cache/config.json'), 1);

    file_put_contents('cache/config.json', json_encode($_POST));

    if($conf['lang'] != $_POST['lang']){
    	header('Location: /settings/');
    }
}

if(isset($_POST['jabber_stop'])){
   file_put_contents('cache/jabber_off', true);
   $i = 0;
   do{
   	 sleep(1);
   	 if(!file_exists('cache/jabber_off')){
   	 	$i = 21;
   	 }else{
   	 	$i++;
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

if(file_exists($cfg['dir'] . 'cache/config.json')){
    $conf = json_decode(file_get_contents('cache/config.json'), 1);
    if(empty($conf['akey'])) $conf['akey'] = generatePassword(32);
    $_POST = $conf;
    $smarty->assign('conf', $conf);
}

$socket = @stream_socket_server("tcp://0.0.0.0:16818", $errno, $errstr);
if (!$socket){
	$smarty->assign("jabber_start", 1);
}else{
	fclose($socket);
}

?>