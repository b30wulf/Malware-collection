<?php
//error_reporting(-1);
$smarty->allow_php_tag = true;
$list = array();
get_function('smarty_assign_add');
get_function('size_format');
unset($_SESSION['gsearch']);
/*
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

$mysqli->query('SELECT * FROM bf_filters ORDER by !ISNULL(host), parent_id ASC', null, 'catalog_item_load');

$smarty->assign("catalog", $list);
*/
$proc = scandir('cache/imports/proc/');
unset($proc[0], $proc[1]);

$pr = '';
foreach($proc as $k => $p){
	$proc[$k] = explode('|', file_get_contents('cache/imports/proc/' . $p));
	if(!empty($proc[$k])){
		if($k & 1 == 1){
			$bg = 'bg1';
		}else{
			$bg = 'bg2';
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

smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.min.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.cookie.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.treeview.min.js"></script>');

?>