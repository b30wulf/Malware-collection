<?php
error_reporting(-1);

ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
$dir = realpath('.');
$count = 0;
/*
$files = $mysqli->query('SELECT * FROM bf_unnecessary WHERE (type = \'5\')');
foreach($files as $file){
	$fi = $dir . '/logs/unnecessary/fgr/' . $file->md5;
	if(!file_exists($fi)){
		echo $fi . "<br>";
		$mysqli->query('delete from bf_unnecessary where (id = \''.$file->id.'\')');
		$count++;
	}
}
*/
$files = $mysqli->query('SELECT * FROM bf_unnecessary WHERE (type = \'6\')');
foreach($files as $file){
	$fi = $dir . '/logs/unnecessary/gra/' . $file->md5;
	if(!file_exists($fi)){
		//echo $fi . "<br>";
		$mysqli->query('delete from bf_unnecessary where (id = \''.$file->id.'\')');
		$count++;
	}
}

echo $count;
?>