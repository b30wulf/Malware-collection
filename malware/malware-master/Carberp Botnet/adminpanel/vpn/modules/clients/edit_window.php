<?php

function generatePassword ($length = 8){	$password = '';
	$possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
	$i = 0;
	while ($i < $length){		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {			$password .= $char;
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

get_function('iptables');
get_function('ip_rule');

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if($_SESSION['user']->config['infoacc'] == '1'){    
    $sql = '';
    
    foreach($_SESSION['user']->config['servers'] as $key => $item){
        $sql .= ' OR (id = \''.$key.'\')';
    }
    $sql = preg_replace('~^ OR ~', '', $sql);
    
    if(!empty($sql)){
        $servers = $mysqli->query('SELECT id,ip,name FROM bf_servers WHERE ('.$sql.') AND (enable = \'1\')', null, null, false);
    }
}else{
    $servers = $mysqli->query('SELECT id,ip,name FROM bf_servers WHERE (enable = \'1\')', null, null, false);
}

$smarty->assign("servers", $servers);

if(!empty($Cur['id'])){
	$item = $mysqli->query('SELECT *, NOW() `now` from bf_clients WHERE id = '.$Cur['id'].' LIMIT 1');
	$smarty->assign("client", $item);
	
	if($item->id == $Cur['id']){
		if(isset($_POST['submit'])){
			if($_POST['autocheck'] == 'on'){
				$_POST['autocheck'] = true;
			}
			
			if($_SESSION['user']->config['infoacc'] == '1'){    
				$ct = (strtotime($item->now) - strtotime($item->post_date));
				if($ct < 120){
					$bad_form['times'] = $lang['times'];
					$bad_form['timez'] = $lang['timez'] . (120-$ct) . ' sec';
					$FORM_BAD = 1;
				}
			}
			
			if(empty($_POST['enable'])){
				$bad_form['enable'] = '"' . $lang['enable'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}else{
				if($_POST['enable'] != 1 && $_POST['enable'] != 'no'){
					$bad_form['enable'] = $lang['nbpn2'];
					$FORM_BAD = 1;
				}
			}
			
			if($_SESSION['user']->config['infoacc'] == '1'){
				$_POST['autocheck'] = true;
				if($_POST['server'] == 0){
					$bad_form['server'] = '"' . $lang['server'] . '" - ' . $lang['asdqwe'];
					$FORM_BAD = 1;
				}
			}else{
				if($_POST['autocheck'] == true && $_POST['server'] == 0){
					$bad_form['autocheck'] = '"' . $lang['autocheck'] . '" - ' . $lang['asdqwe'];
					$FORM_BAD = 1;
				}
			}
			
			if($FORM_BAD <> 1){
				if($_POST['enable'] == 'no') $_POST['enable'] = 0;
				
				$item->enable = $_POST['enable'];
				$item->desc = $_POST['desc'];
				$item->server = $_POST['server'];
				$item->autocheck = $_POST['autocheck'];

				iptables_decode();
				$ret = iptables_match($item->inip . '/32');
				if($ret['count'] > 0){
					foreach($ret['tables'] as $tk => $ti){
						foreach($ti as $ak => $ai){
							krsort($ai, SORT_NUMERIC);
							foreach($ai as $zk => $zi){
								system_to('/sbin/iptables -t mangle -D drops  ' . ($zk+1));
							}
						}
					}
					suexec();
				}
				
				$ret = iptables_match($item->inip . '/255.255.255.255');
				if($ret['count'] > 0){
					foreach($ret['tables'] as $tk => $ti){
						foreach($ti as $ak => $ai){
							krsort($ai, SORT_NUMERIC);
							foreach($ai as $zk => $zi){
								system_to('/sbin/iptables -t mangle -D drops  ' . ($zk+1));
							}
						}
					}
					suexec();
				}
				
				$ret = iptables_match($item->inip);
				if($ret['count'] > 0){
					foreach($ret['tables'] as $tk => $ti){
						foreach($ti as $ak => $ai){
							krsort($ai, SORT_NUMERIC);
							foreach($ai as $zk => $zi){
								system_to('/sbin/iptables -t mangle -D drops  ' . ($zk+1));
							}
						}
					}
					suexec();
				}
				
				if($_POST['enable'] != 1){
					$ipt = '-A drops -t mangle -s '.$item->inip.'/32 -j DROP';
					if(iptables_search('mangle', 'drops', $ipt) == false){
						$ipt = '-A drops -t mangle -s '.$item->inip.'/255.255.255.255 -j DROP';
						if(iptables_search('mangle', 'drops', $ipt) == false){
							$ipt = '-A drops -t mangle -s '.$item->inip.' -j DROP';
							if(iptables_search('mangle', 'drops', $ipt) == false){
								system_to('/sbin/iptables ' . $ipt);
								suexec();
							}
						}
					}
				}else{
					if($item->autocheck == 1){
						$ipt = '-A drops -t mangle -s '.$item->inip.'/32 -o ! tun'.$item->server.' -j DROP';
						if(iptables_search('mangle', 'drops', $ipt) == false){
							$ipt = '-A drops -t mangle -s '.$item->inip.'/255.255.255.255 -o ! tun'.$item->server.' -j DROP';
							if(iptables_search('mangle', 'drops', $ipt) == false){
								$ipt = '-A drops -t mangle -s '.$item->inip.' -o ! tun'.$item->server.' -j DROP';
								if(iptables_search('mangle', 'drops', $ipt) == false){
									system_to('/sbin/iptables ' . $ipt);
									suexec();
								}
							}
						}
					}
					
					$ip_rule = iprule_decode();
					
					$item->prio = (1000+$item->id);
					$item->table = (1000+$item->server);
					$ip_status = iprule_search($item->prio, $item->inip . '/32', $item->table);
					
					if($ip_status != $item->prio){
						system_to('sudo /sbin/ip rule del prio ' . $item->prio);
						system_to('sudo /sbin/ip rule add prio ' . $item->prio . ' from ' . $item->inip . '/32' . ' table ' . $item->table);
						suexec();
					}
				}				
				
				if($mysqli->query('update bf_clients set `desc` = \''.$item->desc.'\', `enable` = \''.$item->enable.'\', `server` = \''.$_POST['server'].'\', `autocheck` = \''.$item->autocheck.'\', post_date = NOW() WHERE (id = \''.$item->id.'\')') == false){
					$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
				}else{
					$smarty->assign("save", true);
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
		}
	}
}

//header('Location: /clients/');



?>