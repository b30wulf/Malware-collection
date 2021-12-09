<?php

if(!file_exists('get_config')){
	function get_config(){
		global $dir;
		
		if(is_array($dir)){
			$d = $dir['site'];
		}else{
			$d = $dir;
		}
		
		if(file_exists($d . 'includes/config.cfg')){
			eval(ioncube_read_file($d . 'includes/config.cfg'));
			return $cfg_db;
		}elseif($d . 'includes/config.php'){
			include_once($d . 'includes/config.php');
			return $cfg_db;
		}
	}
}

?>