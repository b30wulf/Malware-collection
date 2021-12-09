
if(!empty($delete_id)){
	$mysqli->query('delete from bf_links where (id = \''.$delete_id.'\') LIMIT 1');
}

$r = $mysqli->query("select * from bf_links");
$cmds = array();
while($row = $r->fetch_array()){
	$cmds[] = $row;
}

print(json_encode($cmds));