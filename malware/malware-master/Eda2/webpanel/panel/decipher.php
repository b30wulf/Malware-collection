<?php

if(!isset($_POST) || empty($_POST['privatekey'])) {
	exit;
}

set_include_path(get_include_path() . PATH_SEPARATOR . 'lib');
include('lib/Crypt/RSA.php');

$rsa = new Crypt_RSA();

$privatekey = $_POST['privatekey'];
$ciphertext = $_POST['aesencrypted'];
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$rsa->loadKey($privatekey);
echo $rsa->decrypt(base64_decode($ciphertext));

?>