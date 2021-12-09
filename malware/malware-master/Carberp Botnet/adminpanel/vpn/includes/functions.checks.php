<?php

function pregtrim($str){
   return preg_replace("/[^\x20-\xFF]/","",@strval($str));
}

function check_int($int){
    $int=trim(pregtrim($int));
    if ("$int"==intval($int)){
        return intval($int);
    }else{
        return false;
    }
}

function check_email($mail) {
    $mail=trim(pregtrim($mail));
	if (strlen($mail)==0) return false;
	if (!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|ru|ua|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)){ return False; }else{ return $mail;}
}

function check_icq($icq){
	$icq=trim(pregtrim($icq));
	if (preg_match("!^[0-9]{5,15}$!",$icq)) return $icq;
	return false;
}
?>