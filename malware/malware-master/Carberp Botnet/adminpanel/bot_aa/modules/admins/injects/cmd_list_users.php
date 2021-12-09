
$result = $mysqli->query('SELECT id,login,password,expiry_date FROM bf_users');
while($row = $result->fetch_object()){
	$users[] = $row;
}
$users['time'] = time();
print(json_encode($users));
