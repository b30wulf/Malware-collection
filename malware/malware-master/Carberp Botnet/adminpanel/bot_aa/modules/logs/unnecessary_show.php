<?php

//header('Content-type: text/plain');

function test($d){	print_r($d);
}

function data($d){	$d = gzinflate($d);
	//$d = convert_to($d);
	$d = str_replace('[,]', '', $d);
	$d = str_replace('?|POST:', "\r\n\r\nPOST:\r\n\r\n", $d);
	$d = str_replace('&', "\r\n", $d);
	$d = str_replace('[~]', '', $d);
	return htmlspecialchars($d);
}

$cp = 0;

function get($r){	global $cp;
	print(data($r->data). "<hr>");
	$cp++;
}

if(!empty($Cur['id']) && !empty($Cur['str'])){
	if(!empty($Cur['page'])){		$lp = $Cur['page'] * 100;
	}else{		$lp = '0';
	}

	$name_p = mb_substr($Cur['str'], 0, 2);
	if(!preg_match('~^([a-zA-Z0-9]+)$~',$name_p)) $name_p = 'none';


	print('<div id="cp"></div>');
	print('<span id="pp" style="display: none"><a href="/logs/unnecessary_show-'.$Cur['id'].'.html?str='.$Cur['str'].'&page='.($Cur['page']-1).'">Предыдущая страница</a></span> ');
	print('<span id="np" style="display: none"><a href="/logs/unnecessary_show-'.$Cur['id'].'.html?str='.$Cur['str'].'&page='.($Cur['page']+1).'">Следующая страница</a></span> ');

	//if($Cur['page'] > 0) print('<a href="/unnecessary/show-'.$Cur['id'].'.html?str='.$Cur['str'].'&page='.($Cur['page']-1).'">Предыдущая страница</a> ');
	//print('<a href="/unnecessary/show-'.$Cur['id'].'.html?str='.$Cur['str'].'&page='.($Cur['page']+1).'">Следующая страница</a> ');

	echo '<hr><pre style="word-wrap:break-word">';
	$mysqli->query('SELECT data FROM adm_unnecessary.bf_'.$name_p.' WHERE (host = \''.$Cur['str'].'\') AND (type = \''.$Cur['id'].'\') LIMIT '.$lp.', 100', null, 'get', false);
	echo '</pre>';
    print_r("\r\n");
	print('<script language="javascript" type="application/javascript">');

	print('document.getElementById(\'cp\').innerHTML = \'Записей показано: '.$cp.'\';');

	print('if (100 == '.$cp.'){');
	print('document.getElementById(\'np\').style.display = \'\';');
	print('} ');

	print("\r\n");

	print('if ('.$Cur['page'].' > 0){');
	print('document.getElementById(\'pp\').style.display = \'\';');
	print('} ');

	print('</script>');

	//print('<script language="javascript" type="application/javascript">document.getElementById(\'pages\').innerHTML = \'Записей показано: '.$cp.'\';</script>');
}

exit;
?>