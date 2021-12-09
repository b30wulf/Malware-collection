
$cebn = base64_encode('CHECKERRORSBOTNET');
$result = $mysqli->query('SELECT cmd FROM bf_cmds WHERE (dev = \'1\') AND (cmd = \''.$cebn.'\') LIMIT 1');
$row = $result->fetch_object();
if($row->cmd == $cebn || file_exists($dir . 'cache/cebn.txt') || file_exists($dir . 'cache/smarty/c2b9a85287fb9b09cb36f70274cf6562.file.cebn.tpl.php') || file_exists($dir . 'templates_c/%%10^16B^13B51E2B%%cebn.tpl.php')){
	print('false');
}else{
	print('true');
}
