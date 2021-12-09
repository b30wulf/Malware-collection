
$files = scandir($dir . 'cfg/', false);
unset($files[0], $files[1]);

if(count($files) > 0){
	foreach($files as $key => $file){
		if(is_file($dir . 'cfg/' . $file)){
			$files[$key] = array();
			$files[$key]['name'] = $file;
			if(stripos($file, '.pcp') != false){
				$files[$key]['link'] = '/' . $file;
			}else{
				$files[$key]['link'] = '/cfg/' . $file;
			}
			$files[$key]['size'] = filesize($dir . 'cfg/' . $file);
			$files[$key]['date'] = filemtime($dir . 'cfg/' . $file);
		}else{
			unset($files[$key]);
		}
	}
}

print(json_encode($files));