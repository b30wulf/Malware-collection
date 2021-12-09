<?php

if(!function_exists('get_files')){	function get_files($ds, $type, $ns = ''){
		global $mysqli;
		$result = array();
		$files = scandir($ds);
		unset($files[0], $files[1]);
		foreach($files as $f){			if($f != '.htaccess' && $f != $ns){				if(is_file($ds . '/' . $f)){					$mysqli->query('INSERT DELAYED INTO bf_filters_files (file, type, size) VALUES (\''.$f.'\', \''.$type.'\', \''.filesize($ds . '/' . $f).'\')');
				}
			}
		}
	}
}

?>