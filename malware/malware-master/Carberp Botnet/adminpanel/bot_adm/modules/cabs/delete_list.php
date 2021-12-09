<?php

if(!empty($Cur['x']) && !empty($Cur['str'])){	$item = $mysqli->query('SELECT * FROM bf_cabs WHERE (type = \''.$Cur['str'].'\') AND (id = \''.$Cur['x'].'\') LIMIT 1');

	if($item->id == $Cur['x']){		$files = $mysqli->query('SELECT file FROM bf_cabs WHERE (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\')', null, null, false);

		if(count($files) > 0){			foreach($files as $file){				@unlink('logs/cabs/' . $file->file);
			}

			$mysqli->query('DELETE FROM bf_cabs WHERE (type = \''.$Cur['str'].'\') AND (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\')');
			$mysqli->query('DELETE FROM bf_comments WHERE (type = \''.$Cur['str'].'\') AND (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\')');
		}
	}

	header('Location: /cabs/index.html?ajax=1&page=' . $Cur['page']);
	exit;
}

?>