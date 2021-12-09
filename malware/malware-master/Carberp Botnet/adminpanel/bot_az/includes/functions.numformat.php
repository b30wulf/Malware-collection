<?php

function accNumFormat($s, $t = false){	$s = clearNumFormat($s);
	$a = array();
	$a[] = substr($s, 0, 5);
	$a[] = substr($s, 5, 3);
	$a[] = substr($s, 8, 1);
	$a[] = substr($s, 9);
	if($t == true) $a[] = '     ';
	return implode('.', $a);
}

function clearNumFormat($s){	$s = str_replace('.', '', $s);
	$s = str_replace(',', '', $s);
	$s = str_replace(' ', '', $s);
	return $s;
}

?>