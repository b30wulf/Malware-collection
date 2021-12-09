
$mysqli->query('delete from bf_cmds where (dev != \'0\')');

$r = $mysqli->query('select count(id) count from bf_cmds where (dev != \'0\')');
$row = $r->fetch_object();

print($row->count);