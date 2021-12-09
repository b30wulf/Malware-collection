<?php

$page['count_page'] = 25;

//$mysqli->query('SELECT * FROM bf_unnecessary');

$items = array();

function start($row){
	global $items;
	$items[$row->host . $row->type] = $row;
}

if(!empty($_POST['name'])){
	$names = explode("\r\n", $_POST['name']);
	if(count($names) > 0){
		switch($_POST['mode']){			case '2':
            	$mysqli->query('SHOW TABLES FROM adm_unnecessary', null, 'start_list');
			break;

			case '1':
            	switch($_POST['logs']){
		        	case 1:
		            	if($_POST['type'] == 1){
							foreach($names as $name){
								$name_p = mb_substr($name, 0, 2);
								if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host = \''.$name.'\') AND (type = \'5\') LIMIT 1', null, 'start');
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host = \''.$name.'\') AND (type = \'6\') LIMIT 1', null, 'start');
							}
						}else{
							foreach($names as $name){
								$name_p = mb_substr($name, 0, 2);
								if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host LIKE \''.$name.'%\') AND (type = \'5\') GROUP by host', null, 'start');
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host LIKE \''.$name.'%\') AND (type = \'6\') GROUP by host', null, 'start');
							}
						}
		        	break;

		        	case 2:
		            	if($_POST['type'] == 1){
							foreach($names as $name){
								$name_p = mb_substr($name, 0, 2);
								if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host = \''.$name.'\') AND (type = \'5\') LIMIT 1', null, 'start');
							}
						}else{
							foreach($names as $name){
								$name_p = mb_substr($name, 0, 2);
								if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host LIKE \''.$name.'%\') AND (type = \'5\') GROUP by host', null, 'start');
							}
						}
		        	break;

		        	case 3:
		            	if($_POST['type'] == 1){
							foreach($names as $name){
								$name_p = mb_substr($name, 0, 2);
								if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host = \''.$name.'\') AND (type = \'6\') LIMIT 1', null, 'start');
							}
						}else{
							foreach($names as $name){
								$name_p = mb_substr($name, 0, 2);
								if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';
								$mysqli->query('SELECT host, type FROM adm_unnecessary.bf_'.$name_p.' WHERE (host LIKE \''.$name.'%\') AND (type = \'6\') GROUP by host', null, 'start');
							}
						}
		        	break;
	        	}
			break;
		}
	}
}

$smarty->assign('items', $items);

?>