
if(!empty($delete_sys) || !empty($add_sys) || !empty($save_cmd)){
	$cfg = json_decode(file_get_contents($dir . 'cache/config.json'), true);

    if(!empty($save_cmd) && $cfg['hist']['c'] != $save_cmd) $cfg['hist']['c'] = $save_cmd;

	if(strpos($cfg['hist']['l'], $add_sys . '|') === false) $cfg['hist']['l'] .= $add_sys . '|';
	if(!empty($delete_sys)) $cfg['hist']['l'] = str_replace($delete_sys . '|', '', $cfg['hist']['l']);

	$cfgn = json_decode(file_get_contents($dir . 'cache/config.json'), true);
	$cfgn['hist']['l'] = $cfg['hist']['l'];
	$cfgn['hist']['c'] = $cfg['hist']['c'];
	file_put_contents($dir . 'cache/config.json', json_encode($cfgn));
}

print(file_get_contents($dir . 'cache/config.json'));

