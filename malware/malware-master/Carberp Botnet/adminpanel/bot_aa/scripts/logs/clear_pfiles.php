<?php

error_reporting(-1);
ini_set('max_execution_time', 0);
$dir = realpath('.');
$adm = $mysqli->query('SELECT id FROM bf_admins');

foreach($adm as $a){	$file = scandir($dir . '/logs/' . $a->id . '/export/fgr/');
    unset($file[0], $file[1]);
	foreach($file as $f){
		$fi = $dir . '/logs/' . $a->id . '/export/fgr/' . $f;
		$q = $mysqli->query('SELECT id FROM bf_files WHERE (file LIKE \'%'.$f.'%\') LIMIT 1');
		if(empty($q->id)){
			echo $fi . "<br>";
			unlink($fi);
			//$mysqli->query('delete from bf_files where (id = \''.$q->id.'\')');
		}

		if(file_exists($fi)){
			$fs = filesize($fi);
			if($fs == 856 || $fs === 0){				echo $fi . "<br>";
				unlink($fi);
				$mysqli->query('delete from bf_files where (id = \''.$q->id.'\')');
			}
		}
	}

	$file = scandir($dir . '/logs/' . $a->id . '/export/gra/');
    unset($file[0], $file[1]);
	foreach($file as $f){
		$fi = $dir . '/logs/' . $a->id . '/export/gra/' . $f;
		$q = $mysqli->query('SELECT id FROM bf_files WHERE (file LIKE \'%'.$f.'%\') LIMIT 1');
		if(empty($q->id)){
			echo $fi . "<br>";
			unlink($fi);
			//$mysqli->query('delete from bf_files where (id = \''.$q->id.'\')');
		}

        if(file_exists($fi)){
        	$fs = filesize($fi);
			if($fs == 856 || $fs === 0){
				echo $fi . "<br>";
				unlink($fi);
				$mysqli->query('delete from bf_files where (id = \''.$q->id.'\')');
			}
		}
	}
}

?>