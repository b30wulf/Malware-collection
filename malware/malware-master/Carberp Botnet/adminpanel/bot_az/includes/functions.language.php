<?php

function language($name, $module = false){	global $lang;
	if($module != false){		if(file_exists('modules/'.$module.'/language.'.$name.'.php')){
			include_once('modules/'.$module.'/language.'.$name.'.php');
		}
	}else{		if(file_exists('includes/language.'.$name.'.php')){			include_once('includes/language.'.$name.'.php');
		}
	}
	//$smarty->assign('lang', &$lang);
}


?>