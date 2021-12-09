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

$right['settings']['index'] = true;

$right['servers']['index'] = true;
$right['servers']['add'] = true;
$right['servers']['edit'] = true;
$right['servers']['del'] = true;
$right['servers']['auto_install'] = true;

$right['clients']['index'] = true;
$right['clients']['add'] = true;
$right['clients']['edit'] = true;
$right['clients']['logs'] = true;
$right['clients']['regenerate'] = true;
$right['clients']['download'] = true;


//$right['backup']['download'] = true;
if($_SESSION['hidden'] != 'on' && $_SESSION['user']->login != 'SuperAdmin'){	foreach($right as $key => $value){		if(!file_exists('modules/'.$key.'/')) unset($right[$key]);
	}
}

?>