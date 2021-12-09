
$result = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') LIMIT 1');
$result = $result->fetch_object();

include_once($dir . 'modules/bots/country_code.php');
if(isset($country_code[strtoupper($result->country)])){
	$result->country_code = $result->country;
	$result->country = $country_code[strtoupper($result->country)];
}

print(json_encode($result));