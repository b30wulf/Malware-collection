<?php

$mysqli->query('TRUNCATE TABLE bf_search_task');
$mysqli->query('TRUNCATE TABLE bf_search_result');

header('Location: /main/stat.html');
exit;

?>