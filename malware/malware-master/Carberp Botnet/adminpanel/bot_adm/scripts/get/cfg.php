<?php

$f = scandir('cfg/');
unset($f[0], $f[1]);

foreach($f as $k => $z){	if($z == '.htaccess' || preg_match('~.plug$~', $z) == true){		unset($f[$k]);
	}
}

sort($f);

print(json_encode($f));

?>