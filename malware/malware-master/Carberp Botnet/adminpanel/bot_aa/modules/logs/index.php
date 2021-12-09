<?php
$smarty->allow_php_tag = true;
$list = array();

unset($_SESSION['gsearch']);

function catalog_item_load($parent){
	global $list;
	$parent_id = explode('|', $parent->parent_id);
	unset($parent_id[count($parent_id)-1]);
	$count_id = count($parent_id);
    $parent->sub = array();

	switch($count_id){
		case '0':
			$list[$parent->id] = $parent;
		break;

		case '1':
        	$list[$parent_id[0]]->sub[$parent->id] = $parent;
		break;

		case '2':
        	$list[$parent_id[0]]->sub[$parent_id[1]]->sub[$parent->id] = $parent;
		break;

		case '3':
        	$list[$parent_id[0]]->sub[$parent_id[1]]->sub[$parent_id[2]]->sub[$parent->id] = $parent;
		break;
	}
}

function load_admins($r){	global $admins;
	$admins[$r->id] = $r->name;
}

$mysqli->query('SELECT * FROM bf_filters ORDER by !ISNULL(host), parent_id ASC', null, 'catalog_item_load');

$smarty->assign("catalog", $list);

if(file_exists('cache/pid_import.txt')){
	$count = array();

	$count['1'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \'imports.php\') AND ((status = \'0\') OR (status = \'1\'))'); //start
	$count['2'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \'imports.php\') AND (status = \'2\')'); //parsing
	$count['3'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \'imports.php\') AND ((status != \'0\') AND (status != \'1\') AND (status != \'2\') AND (status != \'255\'))'); //error
	$count['255'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \'imports.php\') AND (status = \'255\')'); //succes

	$count['all'] = array_sum($count); //all
	$count['obra'] = $count['255'] + $count['3'];
	$count['allp'] = number_format(($count['obra'] / $count['all'] * 100), 2);
	$count['ost'] = $count['1'] + $count['2'];
	$count['ostp'] = number_format(($count['ost'] / $count['all'] * 100), 2);
	$count['errp'] = number_format(($count['3'] / $count['all'] * 100), 2);

    $admins = array();
    $mysqli->query("SELECT id,name FROM bf_admins", null, 'load_admins', false);
    $smarty->assign("admins", $admins);

    $tdate = $mysqli->query_name('SELECT MIN(post_date) post_date, CURRENT_TIMESTAMP cur_date FROM bf_threads WHERE (script = \'imports.php\')', null, array('post_date', 'cur_date'));
    $tdate['diffsec'] = (strtotime($tdate['cur_date']) - strtotime($tdate['post_date']));
    $tdate['diff'] = time_math($tdate['diffsec']);
    $tdate['ostsec'] = $tdate['diffsec'] * ($count['all'] - $count['obra']);
    $tdate['ost'] = time_math($tdate['ostsec']);


    $smarty->assign("threads", $mysqli->query('SELECT file, type, size, sizep, cv, pv, unnecessary, post_id, TIMEDIFF(NOW(), post_date) wdate, TIMEDIFF(last_date, NOW()) udate FROM bf_threads WHERE (script = \'imports.php\') AND (status = \'2\')', null, null, false));
	$smarty->assign("counts", $count);

	$size['1'] = $mysqli->query_name('SELECT SUM(sizep) count FROM bf_threads WHERE (script = \'imports.php\')');
	$size['2'] = $mysqli->query_name('SELECT SUM(size) count FROM bf_threads WHERE (script = \'imports.php\')');
	$size['3'] = $size['2'] - $size['1'];
	$size['4'] = $mysqli->query_name('SELECT SUM(size) count FROM bf_threads WHERE (script = \'imports.php\') AND ((status = \'1\') OR (status = \'2\'))');
    $smarty->assign("size", $size);

    $tdate['ostsecr'] = ($tdate['diffsec'] / $size['1']) * $size['3'];
    $tdate['ostr'] = time_math($tdate['ostsecr']);

    $smarty->assign("tdate", $tdate);
}

/*
$proc = scandir('cache/proc/');
unset($proc[0], $proc[1]);

$pr = '';
foreach($proc as $k => $p){
	$proc[$k] = explode('|', file_get_contents('cache/proc/' . $p));
	if(!empty($proc[$k])){
		if($k & 1 == 1){			$bg = 'bg1';
		}else{			$bg = 'bg2';
		}
		$pr .= '<tr align="center" class="'.$bg.'">';
		$pr .= '<td>'.size_format($proc[$k][0]).'</td>';
		$pr .= '<td>'.size_format($proc[$k][1]).'</td>';
		$pr .= '<td>'.number_format(($proc[$k][1] / $proc[$k][0] * 100), 2).'%</td>';
		$pr .= '<td>'.(empty($proc[$k][2]) ? '-' : $proc[$k][2]).'</td>';
		$pr .= '<td>'.(empty($proc[$k][3]) ? '-' : $proc[$k][3]).'</td>';
		$pr .= '<td>'.number_format(($proc[$k][3] / $proc[$k][2] * 100), 2).'%</td>';
		$pr .= '</tr>';
	}
}
unset($proc);
$smarty->assign("proc", $pr);
*/
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.min.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.cookie.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.treeview.min.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/logs.js"></script>');

?>