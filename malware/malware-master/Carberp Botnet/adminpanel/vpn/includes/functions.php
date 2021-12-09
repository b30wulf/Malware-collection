<?php

function print_rm($str){
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}

function get_function($name){	if(file_exists('includes/functions.'.$name.'.php')) include_once('includes/functions.'.$name.'.php');
}

?>