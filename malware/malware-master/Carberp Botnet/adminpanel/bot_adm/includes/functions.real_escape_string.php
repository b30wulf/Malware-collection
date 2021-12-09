<?php

function real_escape_string(&$value){
	global $mysqli;
	$value = str_replace("'", '', $value);
	$value = str_replace('"', '', $value);
    return $mysqli->real_escape_string($value);
}

?>