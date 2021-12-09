<?php

$mysqli->query('delete from bf_admins where (id=\''.$Cur['id'].'\')');

header('Location: /admins/index.html?ajax=1');
?>