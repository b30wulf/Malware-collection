<?php

function iptables_decode(){	global $iptables;
	$iptables = array();
	$type = '';

	exec('sudo /sbin/iptables-save', $ts);
	
	foreach($ts as $s){		$x = substr($s, 0, 1);

		switch($x){
	    	case '*':
	    		$type = str_replace('*', '', $s);
	    		$iptables[$type] = array();
	    	break;

	    	case '-':
	    		if(!empty($type)){
	    			$q = explode(' ', $s);
	    			$iptables[$type][$q[1]]['rule'][] = $s;
	    		}
	    	break;

			case ':':
				if(!empty($type)){
					$s = explode(' ', $s, 3);
					$s[0] = str_replace(':', '', $s[0]);
					$iptables[$type][$s[0]]['police'] = $s[1];
					//$iptables[$type][$s[0]]['code'] = $s[2];
				}
			break;

			default:
				if($s == 'COMMIT') $type = '';
			break;
	    }
	}
}

function iptables_search($t, $a, $s){	global $iptables;

	if(isset($iptables[$t][$a]['rule'])){
		foreach($iptables[$t][$a]['rule'] as $k => $i){			if(strpos($s, '-t ' . $t) != false) $s = str_replace(' -t ' . $t, '', $s);
			if($i == $s) return ($k+1);
		}
	}

	return false;
}

function iptables_match($m){	global $iptables;
	$ret = array();
	$ret['count'] = 0;
	foreach($iptables as $tk => $t){
		foreach($t as $ak => $a){
			if(isset($a['rule'])){
				foreach($a['rule'] as $sk => $s){
					if(strpos($s, $m) != false){
						$ret['tables'][$tk][$ak][$sk] = $s;
						$ret['count'] += 1;
					}
				}
			}
		}
	}
	return $ret;
}

?>