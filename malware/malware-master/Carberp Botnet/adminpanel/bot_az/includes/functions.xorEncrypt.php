<?php

function xorEncrypt($Input, $Pass){
    $PassLength = mb_strlen($Pass);
    $InputLength = mb_strlen($Input);

    for ( $i = 0; $i < $InputLength; $i++ ){
        for ( $z = 0; $z < $PassLength; $z++ ){
            $Input[$i] = chr(ord($Input[$i]) ^ (ord($Pass[$z]) + ($i * $z)));
        }
    }
    
    return $Input;
}

?>