<?php

$items = array();

function start($row){
	global $items;
	$items[$row->host . $row->type] = $row;
}

if(!empty($_POST['name'])){
	$names = explode("\r\n", $_POST['name']);
	if(count($names) > 0){
		switch($_POST['logs']){			case 1:
				if($_POST['type'] == 1){					foreach($names as $name){						$mysqli->query('SELECT host, type FROM bf_unnecessary WHERE (host = \''.$name.'\') AND (type = \'5\' OR type = \'6\')', null, 'start');
					}
				}else{					foreach($names as $name){						$mysqli->query('SELECT host, type FROM bf_unnecessary WHERE (host LIKE \'%'.$name.'%\') AND (type = \'5\' OR type = \'6\')', null, 'start');
					}
				}
			break;

			case 2:
				if($_POST['type'] == 1){					foreach($names as $name){						$mysqli->query('SELECT host, type FROM bf_unnecessary WHERE (host = \'%'.$name.'\') AND (type = \'5\')', null, 'start');
					}
				}else{					foreach($names as $name){						$mysqli->query('SELECT host, type FROM bf_unnecessary WHERE (host LIKE \'%'.$name.'%\') AND (type = \'5\')', null, 'start');
					}
				}
			break;

			case 3:
				if($_POST['type'] == 1){					foreach($names as $name){						$mysqli->query('SELECT host, type FROM bf_unnecessary WHERE (host = \''.$name.'\') AND (type = \'6\')', null, 'start');
					}
				}else{					foreach($names as $name){						$mysqli->query('SELECT host, type FROM bf_unnecessary WHERE (host LIKE \'%'.$name.'%\') AND (type = \'6\')', null, 'start');
					}
				}
			break;
		}
	}
}

$smarty->assign('items', $items);

?>