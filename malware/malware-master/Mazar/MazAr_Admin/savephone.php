<?

include_once("config.php");

$refish = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';

if(stripos($refish, $adminphp) === false)
{
die(header("Location: ".$refish));
}

if(isset($_REQUEST["id"])) {
	$uniqid = $_REQUEST["id"];
}
else {
	die(header("Location: ".$refish));
}

if(isset($_REQUEST["phone"])) {
	$phone = $_REQUEST["phone"];
}
else {
	die(header("Location: ".$refish));
}

$link = mysql_connect($mysql_host, $mysql_user, $mysql_pwd);
if (!$link) {
    die(mysql_close($link));
}
mysql_select_db($mysql_db) || die(mysql_close($link));
mysql_query("SELECT phone FROM stata where (imei='$uniqid')") or die(mysql_close($link));
mysql_query("INSERT INTO stata (imei, phone) VALUES ('$uniqid', '$phone') ON DUPLICATE KEY UPDATE imei = '" . $uniqid . "', phone = '" . $phone . "'") or die(mysql_close($link));
mysql_close($link);

file_put_contents("temp/phone".$uniqid, $phone);

die(header("Location: ".$refish));
