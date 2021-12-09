<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id']) && !empty($Cur['str'])){	switch($Cur['str']){
		case 'me':
        	$filter = new stdClass();
        	$filter->str = $id;
        	$filter->name = $lang['fms'];
        	$filter->id = 'me';
		break;

		case 'ft':
        	$filter = new stdClass();
        	$filter->str = $id;
        	$filter->name = $lang['ftpc'];
        	$filter->id = 'ft';
		break;

		case 'ep':
        	$filter = new stdClass();
        	$filter->str = $id;
        	$filter->name = $lang['fpp'];
        	$filter->id = 'ep';
		break;

		case 'rd':
        	$filter = new stdClass();
        	$filter->str = $id;
        	$filter->name = $lang['frdc'];
        	$filter->id = 'rd';
		break;

		default:
        	$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id=\''.$Cur['str'].'\') LIMIT 1');
		break;
	}

	if($filter->id == $Cur['str']){		$log = $mysqli->query('SELECT * FROM bf_filter_'.$filter->id.' WHERE (id=\''.$Cur['id'].'\') LIMIT 1');

		$smarty->assign('filter',$filter);
		$smarty->assign('log',$log);
	}
}


?>