
require_once($dir . 'classes/smarty/Smarty.class.php');
$smarty = new Smarty;

$smarty->assign('site_data', 'empty.tpl');
$smarty->compile_check = true;
$smarty->caching = false;
$smarty->template_dir = $dir . 'templates/';
$smarty->compile_dir = $dir . 'cache/smarty/';
$smarty->cache_dir = $dir . 'cache/smarty/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
$smarty->assign('config', $config);
