
if($dest == true){	if(file_exists($dir . 'cache/start.db')){
		unlink($dir . 'cache/start.db');
	}else{
		file_put_contents($dir . 'cache/start.db', '1');
	}}

if(file_exists($dir . 'cache/start.db')){	print('OK!');
}