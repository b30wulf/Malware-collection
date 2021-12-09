<?php

class ServiceCfg {
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
    
    function setCfg($cfg = ''){
        if(empty($cfg)) return false;
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.$cfg.'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
	return true;
    }
    
    function getCfg(){
        return $this->bot->info;
    }
}

RegisterService("ServiceCfg");

?>