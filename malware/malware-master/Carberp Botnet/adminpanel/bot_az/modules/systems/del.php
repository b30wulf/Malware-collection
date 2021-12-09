<?php

if(!empty($Cur['id'])){
	 $item = $mysqli->query('SELECT * from bf_systems WHERE id = '.$Cur['id'].' LIMIT 1');

	if($item->id == $Cur['id']){
		$mysqli->query('DELETE FROM bf_systems WHERE (id = \''.$item->id.'\') LIMIT 1');
	}
}

header('Location: /systems/index.html');
exit;

?>