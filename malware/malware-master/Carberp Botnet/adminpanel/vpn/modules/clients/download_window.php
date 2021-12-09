<?php
//error_reporting(-1);
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('rc');

function system_to($cmd){
    global $to;
    $to .= $cmd . "\n\n";
}

function suexec($deamon = false){
    global $to;
    $file = '/tmp/phpexec_'.mt_rand().'.sh';
    file_put_contents($file, '#!/bin/sh' . "\n");
    file_put_contents($file, $to . "\n", FILE_APPEND);
    @system('sudo /bin/chmod 777 ' . $file);
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);
    $to = '';
}

if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT * from bf_clients WHERE id = '.$Cur['id'].' LIMIT 1');
    
    if($item->id == $Cur['id']){
        $smarty->assign("client", $item);
        
        if(!empty($Cur['x'])){
            switch($Cur['x']){
                case 'direct':
                    $fn = 'cache/download/direct/' . $item->name . '_' . $config['ip'] . '.zip';
                    
                    if(file_exists($fn)) unlink($fn);
                    
                    $zip = new ZipArchive;
                    $res = $zip->open($fn, ZIPARCHIVE::OVERWRITE);
                    if($res === TRUE){                       
                        $zip->addEmptyDir($config['ip'] . '/');
                        $zip->addFromString($config['ip'] . '/ca.crt', file_get_contents('/etc/openvpn/tun/ca.crt'));
                        $zip->addFromString($config['ip'] . '/' . $item->name.'.crt', $item->crt);
                        $zip->addFromString($config['ip'] . '/' . $item->name.'.key', $item->key);
                        $zip->addFromString($config['ip'] . '/ta.key', file_get_contents('/etc/openvpn/tun/ta.key'));
                        
                        $client_conf = $smarty->fetch("modules/clients/default-client.tpl");
                        
                        $zip->addFromString($item->name.'_'.$config['ip'].'.ovpn', $client_conf);
                        
                        $zip->close();
                    }
                break;
            
                case 'sendspace':
                    $fn = 'cache/download/sendspace/' . $item->name . '_' . $config['ip'] . '.zip';
                    $fn7 = 'cache/download/sendspace/' . $item->name . '_' . $config['ip'] . '.7z';
                    $fni = $fn7 . '.info';
                    $pass = generatePassword(12);
                    
                    if(file_exists($fn)) unlink($fn);
                    if(file_exists($fn7)) unlink($fn7);                    
                    
                    $zip = new ZipArchive;
                    $res = $zip->open($fn, ZIPARCHIVE::OVERWRITE);
                    if($res === TRUE){
                        $zip->addEmptyDir($config['ip'] . '/');
                        $zip->addFromString($config['ip'] . '/ca.crt', file_get_contents('/etc/openvpn/tun/ca.crt'));
                        $zip->addFromString($config['ip'] . '/' . $item->name.'.crt', $item->crt);
                        $zip->addFromString($config['ip'] . '/' . $item->name.'.key', $item->key);
                        $zip->addFromString($config['ip'] . '/ta.key', file_get_contents('/etc/openvpn/tun/ta.key'));
                        
                        $client_conf = $smarty->fetch("modules/clients/default-client.tpl");
                        
                        $zip->addFromString($item->name.'_'.$config['ip'].'.ovpn', $client_conf);
                        
                        $zip->close();
                    }
                    
                    system_to('cd cache/download/sendspace/');
                    system_to('/usr/bin/7za a -t7z -mhe=on ' . basename($fn7) . ' ' . realpath($fn) . ' -r -mx=9 -p' . $pass . ' -w' . realpath(dirname($fn7)));
                    suexec();

                    $uagent = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13';
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		    curl_setopt($ch, CURLOPT_URL, 'http://www.sendspace.com/');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_ENCODING, '');
		    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cache/cookie.txt');
                    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cache/cookie.txt');
		    $return = curl_exec ($ch);
		    curl_close ($ch);
                    
                    preg_match_all('~<form(.*?)</form>~is', $return, $out);
                    preg_match_all('~<form method="post" action="(.*)" enctype="multipart/form-data"(.*)>(.*?)</form>~isU', $out[0][2], $out);
                    $url = $out[1][0];
                    preg_match_all('~<input(.*)type=\"hidden\"(.*)name=\"(.*)\"(.*)?value=\"(.*)\"(.*)?>~isU', $out[0][0], $out, PREG_SET_ORDER);
                    
                    $postfields = array();
                    foreach($out as $val) $postfields[$val[3]] = $val[5];
                    
                    $post = array();
                    $post['MAX_FILE_SIZE'] = $postfields['MAX_FILE_SIZE'];
                    $post['UPLOAD_IDENTIFIER'] = $postfields['UPLOAD_IDENTIFIER'];
                    $post['DESTINATION_DIR'] = $postfields['DESTINATION_DIR'];
                    $post['js_enabled'] = $postfields['js_enabled'];
                    $post['signature'] = $postfields['signature'];
                    $post['upload_files'] = $postfields['upload_files'];
                    $post['terms'] = '1';
                    $post['file[]'] = '';
                    $post['description[]'] = '';
                    $post['upload_file[]'] = '@' . realpath($fn7);
                    $post['recpemail_fcbkinput'] = 'recipient@email.com';
                    $post['ownemail'] = '';
                    $post['recpemail'] = '';
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cache/cookie.txt');
                    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cache/cookie.txt');
                    $return = curl_exec ($ch);
                    curl_close ($ch);
                    
                    preg_match_all('~<h[0-9]+>Download Link<\/h[0-9]+>(.*)<div class="urlbox small">(.*)<a href="(.*)"(.*)>(.*)<\/div>~isU', $return, $o);
                    $a['download'] = $o[3][0];
                    preg_match_all('~<h[0-9]+>Delete File Link<\/h[0-9]+>(.*)<div class="urlbox small">(.*)<a(.*)href="(.*)"(.*)>(.*)<\/div>~isU', $return, $o);
                    $a['delete'] = $o[4][0];
                    
                    file_put_contents($fni, 'SendSpace Link: ' . $a['download'] . "\r\n\r\n");
                    file_put_contents($fni, 'SendSpace DelLink: ' . $a['delete'] . "\r\n\r\n", FILE_APPEND);

                    file_put_contents($fni, 'Password: ' . $pass, FILE_APPEND);
                break;
            }
        }
        
        
        $download = array();
        
        if(file_exists('cache/download/direct/' . $item->name . '_' . $config['ip'] . '.zip')){
            $download['direct'] = array();
            $download['direct']['file'] = 'cache/download/direct/' . $item->name . '_' . $config['ip'] . '.zip';
        }else{
            $download['direct'] = false;
        }
        
        if(file_exists('cache/download/sendspace/' . $item->name . '_' . $config['ip'] . '.7z')){
            $download['sendspace'] = array();
            $download['sendspace']['file'] = 'cache/download/sendspace/' . $item->name . '_' . $config['ip'] . '.7z';
            $download['sendspace']['info'] = file_get_contents('cache/download/sendspace/' . $item->name . '_' . $config['ip'] . '.7z.info');
        }else{
            $download['sendspace'] = false;
        }

        $smarty->assign("download", $download);
    }
}

?>