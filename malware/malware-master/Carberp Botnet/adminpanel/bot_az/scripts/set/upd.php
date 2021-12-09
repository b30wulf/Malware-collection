<?php

ini_set('error_reporting', -1);
header("Pragma: no-cache");
header("Expires: 0");

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.php');

get_function('first');
get_function('numformat');
get_function('get_config');
get_function('mb_unserialize');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();
$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true, false);

function get_transfers($row){
    global $mysqli;
    $row->info = json_decode(base64_decode($row->info));
    $mysqli->query('update bf_transfers set drop_id = \''.$row->info->drop->id.'\' WHERE (id = \''.$row->id.'\')');
}
/*
$mysqli->query('SELECT id, info FROM bf_transfers WHERE drop_id = \'\'', null, 'get_transfers', false);

$mysqli->query('UPDATE bf_balance set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_bots set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_comments set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_drops set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_hidden set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_logs set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_logs_passiv set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_log_info set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_manuals set prefix=upper(prefix), uid=upper(uid)');
$mysqli->query('UPDATE bf_transfers set prefix=upper(prefix), uid=upper(uid)');
*/

function get_bots($row){
    global $mysqli;
    //$row->info = json_decode(base64_decode($row->info));
    $row->info = base64_decode($row->info);
    
    if(strpos($row->info, '{') !== 0 && strpos($row->info, '[') !== 0){
        $row->info = base64_decode($row->info);
        
        if(strpos($row->info, '{') !== 0 && strpos($row->info, '[') !== 0){
            $row->info = base64_decode($row->info);
            
            if(strpos($row->info, '{') !== 0 && strpos($row->info, '[') !== 0){
                $row->info = base64_decode($row->info);
                
                if(strpos($row->info, '{') !== 0 && strpos($row->info, '[') !== 0){
                    $row->info = base64_decode($row->info);
                }else{
                    $mysqli->query('update bf_bots set info = \''.base64_encode($row->info).'\' WHERE (id = \''.$row->id.'\')');
                }
            }else{
                $mysqli->query('update bf_bots set info = \''.base64_encode($row->info).'\' WHERE (id = \''.$row->id.'\')');
            }
        }else{
            $mysqli->query('update bf_bots set info = \''.base64_encode($row->info).'\' WHERE (id = \''.$row->id.'\')');
        }
        
    }
    
    
    //$mysqli->query('update bf_transfers set drop_id = \''.$row->info->drop->id.'\' WHERE (id = \''.$row->id.'\')');
}

$mysqli->query('SELECT id, info FROM bf_bots', null, 'get_bots', false);

?>