<?php

$list = array();
get_function('smarty_assign_add');
function catalog_item_load($parent){
	global $list;
	$parent_id = explode('|', $parent->parent_id);
	unset($parent_id[count($parent_id)-1]);
	$count_id = count($parent_id);
    $parent->sub = array();

	switch($count_id){		case '0':
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

smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.min.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.cookie.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.treeview.min.js"></script>');
//smarty_assign_add('javascript_end', '<script type="text/javascript" src="/js/add_filter.js"></script>');


?>