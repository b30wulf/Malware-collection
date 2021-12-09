
set_time_limit(0);
ini_set('max_execution_time','0');
error_reporting(-1);
ini_set('error_reporting', -1);
ini_set('memory_limit', '256M');
header('Content-type: text/plain');
header('Cache-Control: no-cache, must-revalidate');

if(!isset($dir) && empty($dir)){
	switch($cur_file){
		case '/index.php':
        	$dir = str_replace('\\', '/', realpath('.')) . '/';
		break;

		default:
        	$dir = str_replace('/scripts' . dirname($cur_file), '', str_replace('\\', '/', realpath('.'))) . '/';
		break;
	}
}

