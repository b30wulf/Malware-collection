
if(!empty($delete_id)){
	$mysqli->query('delete from bf_cmds where (id = \''.$delete_id.'\') LIMIT 1');
}

$r = $mysqli->query("select * from bf_cmds");
$cmds = array();

if($config['scramb'] == 1){
	while($row = $r->fetch_array()){
		$row['cmd'] = rc_decode($row['cmd'], $rc['key']);
		$cmds[] = $row;
	}
}else{
	while($row = $r->fetch_array()){
		$cmds[] = $row;
	}
}

print(json_encode($cmds));
