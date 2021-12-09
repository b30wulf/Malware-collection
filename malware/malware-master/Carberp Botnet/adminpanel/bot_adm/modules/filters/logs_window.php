<?php

if($Cur['x']){	$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
}else{	$smarty->assign('rand_name', $Cur['x']);
}
get_function('html_pages');
$page['count_page'] = 100;

$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
$id = $filter->id;

if($id != $Cur['id']){
	exit;
}

$smarty->assignByRef('filter', $filter);

if($Cur['str'] == 'fgr_fields'){
    $filter->fields = explode(',', urldecode($filter->fields));
    $filter->fc = count($filter->fields);
    $filter->fields = array_slice($filter->fields, ($Cur['page']*$page['count_page']), $page['count_page']);
    $smarty->assign('pages', html_pages('#logs-'.$Cur['id'].'.html?str=fgr_fields&window=1&x=' . $smarty->tpl_vars['rand_name']->value, $filter->fc, $page['count_page'], 1, 'load_data_fgr', 'this.href'));

	$smarty->display('modules/filters/fgr_fields.tpl');
	exit;
}

?>