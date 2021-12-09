
$list = array();

if(!empty($delete_sys) || !empty($add_sys)){
	if(file_exists($dir . 'cache/io.db')){
		$flist = $list = explode('|', file_get_contents($dir . 'cache/io.db'));
		if(count($list) > 0){
			foreach($list as $k => $i){
				if(!empty($i)){
					$list[$k] = explode(':', $i);
				}else{
					unset($list[$k]);
				}
			}
		}
	}

	if(strpos(implode('|', $flist), preg_replace('~:(.*)$~isU', ':', $add_sys)) === false){		$list[] = explode(':', $add_sys);
	}

	$si = '';
	if(count($list) > 0){
		foreach($list as $z){
			if(!empty($z) && is_array($z) && !empty($z[0]) && !empty($z[1])){				$si .= implode(':', $z) . '|';
			}
		}
	}

	if(!empty($delete_sys)) $si = preg_replace('~'.$delete_sys.'\:(.*)\|~isU', '', $si);
	file_put_contents($dir . 'cache/io.db', $si);
}

if(file_exists($dir . 'cache/io.db')){
	$list = explode('|', file_get_contents($dir . 'cache/io.db'));
	if(count($list) > 0){
		foreach($list as $k => $i){
			if(!empty($i)){
				$list[$k] = explode(':', $i);
			}else{
				unset($list[$k]);
			}
		}
	}
}

print(json_encode($list) . '[~]' . @filesize($dir . 'cache/data.db') . '|' . @filesize($dir . 'cache/data.bak.db'));

