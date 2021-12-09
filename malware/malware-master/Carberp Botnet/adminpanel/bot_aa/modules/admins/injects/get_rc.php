
get_function('rc');

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

$rc['key'] = rc_decode($config['rc'], 'AUvS8jou0Z9K7Bf9');

