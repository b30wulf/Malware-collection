
function get_function($name){
	global $dir;
	if(file_exists($dir . 'includes/functions.'.$name.'.php')) include_once($dir . 'includes/functions.'.$name.'.php');
}
