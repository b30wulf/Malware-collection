<?php

function sql_inject(&$data){
    $data = str_ireplace('"', '', $data);
    $data = str_ireplace("'", '', $data);
    $data = str_ireplace("INTO OUTFILE", '', $data);
    $data = str_ireplace("OUTFILE", '', $data);
    $data = str_ireplace("SELECT", '', $data);
    //$data = str_ireplace("INSERT", '', $data);
    //$data = str_ireplace("DELETE", '', $data);
    //$data = str_ireplace("UPDATE", '', $data);
    $data = str_ireplace("UNION", '', $data);
    return $data;
}

?>