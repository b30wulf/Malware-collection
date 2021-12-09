<?php

if(!file_exists('cache/p2p.json')){
    $config = array('private_key_bits' => 2048);
    $res = openssl_pkey_new($config);
    
    openssl_pkey_export($res, $privkey);
    $pubkey=openssl_pkey_get_details($res);
    
    $keys['priv'] = $privkey;
    $keys['pub'] = $pubkey['key'];
    
    file_put_contents('cache/p2p.json', base64_encode(json_encode($keys)));
}else{
    if(!file_exists('cache/p2p.json.bak')){
        copy('cache/p2p.json', 'cache/p2p.json.bak');
    }
    
    $keys = file_get_contents('cache/p2p.json');
    $keys = json_decode(base64_decode($keys), 1);
    
    $res['priv'] = openssl_pkey_get_private($keys['priv']);
    //$pubkey = openssl_pkey_get_details($res['priv']);
}

$smarty->assign('keys', $keys);

if(isset($_POST['submit'])){
    $_POST['domains'] = str_replace("\r", '', $_POST['domains']);
    openssl_private_encrypt($_POST['domains'], $hosts, $keys['priv']);
    $keys['hosts'] = base64_encode($hosts);
    $keys['hosts2'] = $_POST['domains'];
    $keys['time'] = time();
    file_put_contents('cache/p2p.json', base64_encode(json_encode($keys)));
}else{
    if(isset($keys['hosts2']) && !empty($keys['hosts2'])){
        $_POST['domains'] = $keys['hosts2'];
        //openssl_private_decrypt($keys['hosts'], $_POST['domains'], $keys['priv']);
    }
}

?>