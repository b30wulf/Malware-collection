#!/usr/bin/env php
<?php

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';
$dir = realpath('../') . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

file_put_contents('/tmp/jabber_bot.sh', '#!/bin/sh' . "\n");
file_put_contents('/tmp/jabber_bot.sh', 'cd ' . $dir . 'crons/scripts/' . "\n", FILE_APPEND);
file_put_contents('/tmp/jabber_bot.sh', '/usr/bin/env php ' . $dir . 'crons/scripts/jabber_bot.php > /dev/null &', FILE_APPEND);
chmod('/tmp/jabber_bot.sh', 0777);
@system('/tmp/jabber_bot.sh');
unlink('/tmp/jabber_bot.sh');

?>