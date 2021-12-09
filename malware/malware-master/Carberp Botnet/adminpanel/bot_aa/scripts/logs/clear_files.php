<?php
ini_set('max_execution_time', 0);
$dir = realpath('.');

$files = $mysqli->query('SELECT id,file,size FROM bf_files WHERE (post_date < DATE_ADD(NOW(), INTERVAL -1 MONTH))');
//$files = $mysqli->query('SELECT id,file,size FROM bf_files WHERE (post_id = \'7\') OR (post_id = \'10\') OR (post_id = \'14\') OR (post_id = \'15\')');
$size = 0;
foreach($files as $file){	$size += $file->size;
	//echo $dir . '/logs' . $file->file . '<br>';
	//$mysqli->query('delete from bf_files where (id = \''.$file->id.'\')');
	//@unlink($dir . '/logs' . $file->file);
}

echo size_format($size);

?>