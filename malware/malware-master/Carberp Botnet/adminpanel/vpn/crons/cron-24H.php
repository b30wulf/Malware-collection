#!/usr/bin/env php
<?php

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';
$dir = realpath('../') . '/';

file_put_contents('/tmp/recfg.sh', '#!/bin/sh' . "\n");
file_put_contents('/tmp/recfg.sh', 'cd ' . $dir . 'crons/scripts/' . "\n", FILE_APPEND);
file_put_contents('/tmp/recfg.sh', '/usr/bin/env php ' . $dir . 'crons/scripts/recfg.php > /dev/null &', FILE_APPEND);
chmod('/tmp/recfg.sh', 0777);
@system('/tmp/recfg.sh');
unlink('/tmp/recfg.sh');

?>