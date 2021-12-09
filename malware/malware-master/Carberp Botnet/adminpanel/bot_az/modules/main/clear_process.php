<?php

$mysqli->query('TRUNCATE TABLE bf_process');
$mysqli->query('TRUNCATE TABLE bf_process_stats');

header('Location: /main/stat.html');
exit;

?>