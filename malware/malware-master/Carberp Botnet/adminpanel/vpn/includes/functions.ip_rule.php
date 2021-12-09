<?php

function iprule_decode(){	exec('sudo /sbin/ip rule', $ts);

	$ip_rule = array();
	foreach ($ts as $item){
		$item = str_replace("\t", '', $item);
		$item = explode(":", $item, 2);
		$item[1] = explode(' ', $item[1]);
		
		if(strpos($item[1][count($item[1])-1], 'tun') === 0){
			$item[1][count($item[1])-1] = str_replace('tun', '', $item[1][count($item[1])-1]);
			$item[1][count($item[1])-1] = 1000+$item[1][count($item[1])-1];
		}
		
		$ip_rule[$item[0]] = $item[1];
	}
	//print_r($ip_rule);
	return $ip_rule;
}

function iprule_search($prio, $from, $table){	global $ip_rule;

	if(isset($ip_rule[$prio])){
		if($ip_rule[$prio][1] == $from){
			if($ip_rule[$prio][3] == $table){
				return $prio;
			}else{
				return 2;
			}
		}else{
			return 1;
		}
	}else{
		return false;
	}
}

?>