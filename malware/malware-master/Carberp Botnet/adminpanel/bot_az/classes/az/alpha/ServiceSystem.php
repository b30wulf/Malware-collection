<?php
 
class ServiceSystem {
    private $bot, $system, $mysqli;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
    }
    
    function Log($log = ''){
        if(empty($log)) return false;
        
        $this->db->query("INSERT DELAYED INTO bf_logs (`userid`, `prefix`, `uid`, `ip`, `log`, system) VALUES ('".$this->bot->userid."', '".$this->bot->prefix."', '".$this->bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$log."', '".$this->system->nid."')");
    }
}

RegisterService("ServiceSystem");