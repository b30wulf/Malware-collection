<?php

function generatePassword ($length = 8){
	$password = '';
	$possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
	$i = 0;
	while ($i < $length){
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	$password = str_replace('BJB', 'JBJ', $password);
	return $password;
}

function system_to($cmd){
    global $to;
    $to .= $cmd . "\n\n";
}


function suexec($deamon = false){
    global $to;
    $file = '/tmp/phpexec_'.mt_rand().'.sh';
    file_put_contents($file, '#!/bin/sh' . "\n");
    file_put_contents($file, $to . "\n", FILE_APPEND);
    @system('sudo /bin/chmod 777 ' . $file);
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);
    $to = '';
}


$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

$servers = $mysqli->query('SELECT id,ip,name FROM bf_servers WHERE (enable = \'1\')');
$smarty->assign("servers", $servers);

if(isset($_POST['submit'])){
	if($_POST['autocheck'] == 'on'){
		$_POST['autocheck'] = true;
	}

	if(empty($_POST['name'])){
		$bad_form['name'] = '"' . $lang['name'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}else{
		if(strlen($_POST['name']) > 5){
			$bad_form['name'] = $lang['nbpn2'];
			$FORM_BAD = 1;
		}elseif(!preg_match('~^([a-z0-9]+)$~', $_POST['name'])){
			$bad_form['name'] = $lang['nbpn2'];
			$FORM_BAD = 1;
		}
	}

	if(empty($_POST['days'])){
		$bad_form['days'] = '"' . $lang['days'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}else{
		if(strlen($_POST['days']) > 365){
			$bad_form['days'] = $lang['nbpn2'];
			$FORM_BAD = 1;
		}
	}

	if(empty($_POST['enable'])){
		$bad_form['enable'] = '"' . $lang['enable'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}else{
		if($_POST['enable'] != 1 && $_POST['enable'] !== 0){
			$bad_form['enable'] = $lang['nbpn2'];
			$FORM_BAD = 1;
		}
	}

	if($_POST['autocheck'] == true && $_POST['server'] == 0){
		$bad_form['autocheck'] = '"' . $lang['autocheck'] . '" - ' . $lang['asdqwe'];
		$FORM_BAD = 1;
	}



	if($FORM_BAD <> 1){
		$_POST['ip'] = $config['ip'];
		$_POST['port'] = $config['port'];
		$_POST['esa'] = $config['esa'];

		//$outconf = $smarty->fetch('modules/clients/default-conf.ovpn.tpl');

		$outbuild = $smarty->fetch('modules/clients/default-build.tpl');
		$conf['time'] = time();

		/*
		file_put_contents('/tmp/build-' . $conf['time'], $outbuild);
		exec('sudo -u root chmod -R 777 /tmp/build-' . $conf['time']);
		exec('sudo -u root /tmp/build-' . $conf['time'] . ' ' . $conf['user']);
		exec('sudo -u root chmod -R 777 ' . $conf['dir']['keys']);
		exec('sudo -u root chmod -R 777 ' . $config['esa'] . 'keys/*.*');
		//@unlink('/tmp/build-' . $conf['time']);
		*/

		system_to($smarty->fetch('modules/clients/default-build.tpl'));
		system_to('/bin/chmod 777 ' . $_POST['esa'] . '* -R');
		suexec();

		if(file_exists($config['esa'] . 'keys/' . $_POST['name'] . '.crt') && file_exists($config['esa'] . 'keys/' . $_POST['name'] . '.key') && filesize($config['esa'] . 'keys/' . $_POST['name'] . '.crt') > 0 && filesize($config['esa'] . 'keys/' . $_POST['name'] . '.key') > 0){
			$key = file_get_contents($config['esa'] . 'keys/' . $_POST['name'] . '.key');
			$crt = file_get_contents($config['esa'] . 'keys/' . $_POST['name'] . '.crt');
			$expiry_date = '';

			preg_match_all('~Not After : (.*) GMT~is', $crt, $out);
			if(!empty($out[1][0])){
				$expiry_date = date('Y-m-d H:i:s', strtotime($out[1][0] . ' GMT'));
				unset($out);
			}else{
				$expiry_date = '0000-00-00 00:00:00';
			}

			if(!empty($key) && !empty($crt)){
				$insert_id = $mysqli->query("INSERT INTO bf_clients (`name`, `crt`, `key`, `server`, `expiry_date`, `enable`, `autocheck`, `desc`) VALUES ('".$_POST['name']."', '".$crt."', '".$key."', '".$_POST['server']."', '".$expiry_date."', '".$_POST['enable']."', '".$_POST['autocheck']."', '".$_POST['desc']."')");
				if($insert_id == false){
					$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
				}else{
					$smarty->assign("save", true);
				}
			}else{
				$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
			}
		}else{
			$errors .= '<div class="t"><div class="t4" align="center">'.$lang['cadegc'].'</div></div>';
		}
	}else{
		if(count($bad_form) > 0){
			rsort($bad_form);
			for($i = 0; $i < count($bad_form); $i++){
				if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
				$errors .= '<div class="t"><div class="t4" align="center">' . $bad_form[$i] . '</div></div>';
			}
		}
	}
	$smarty->assign("errors", $errors);

}else{
	if(!isset($_POST['days'])) $_POST['days'] = 365;
	if(!isset($_POST['enable'])) $_POST['enable'] = '1';

	$cn = $mysqli->query_name('SELECT COUNT(id) count FROM bf_clients');

	if(!isset($_POST['name'])) $_POST['name'] = 'vpn' . ($cn+1);
}

?>