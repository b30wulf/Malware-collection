<?

include_once("config.php");

function pass() {
	$len = rand(3, 5);
	$b = "QWERTYUPASDFGHJKLZXCVBNMqwertyuopasdfghjkzxcvbnm"; 
	while($len-->0) $s .= $b[mt_rand(0,strlen($b))];
	return $s; 
}

$link = mysql_connect($mysql_host, $mysql_user, $mysql_pwd);
if (!$link) {
    die(mysql_close($link));
}

mysql_select_db($mysql_db) || die(mysql_close($link));

for ($i = 0; $i < 1110; $i++) {

$prefix = "razraz";
$phonearr = array(null, "+".rand(1, 9).rand(100, 999).rand(100, 999).rand(10, 99).rand(10, 99));
$phone = $phonearr[rand(0, 1)];
$smsdefarr = array("com.android.mms", "unknown", "com.google.android.talk");
$smsdef = $smsdefarr[rand(0, count($smsdefarr) - 1)];
$imei = rand(300000000000000, 500000000000000);
$langarr = array("US", "CA", "NZ", "GB", "AU");
$lang = $langarr[rand(0, count($langarr) - 1)];
$opsosarr = array("ZONG", "TELCEL", "Sprint", "Verizon Wireles", "IDEA", "Virgin Mobile", "Bell");
$opsos = $opsosarr[rand(0, count($opsosarr) - 1)];
$modelarr = array("SM-G531H", "ONE TOUCH 6012A", "LGLS991", "SM-G920I", "ZTE T815", "SM-G900P", "SM-J100Y");
$model = $modelarr[rand(0, count($modelarr) - 1)];
$versdk = rand(11, 22);
$user_ip = rand(1000000000, 5000000000);
$country = $langarr[rand(0, count($langarr) - 1)];
$smsgrab = 0;
$isadmin = rand(0, 1);
if($isadmin == 1) file_put_contents("temp/needinject".$imei, "");
if($isadmin == 0) file_put_contents("temp/stopinject".$imei, "");
$tektime = time();
$carta = rand(0, 1);

echo $prefix." ".$phone." ".$smsdef." ".$imei." ".$lang." ".$opsos." ".$model." ".$versdk." ".$user_ip." ".$country." ".$smsgrab." ".$tektime;

mysql_query("INSERT INTO stata (imei, pefixa, phone, smsdef, lang, opsos, model, versdk, ip, country, locksms, restarts, carta, isadmin, tektime) VALUES ('$imei', '$prefix', '$phone', '$smsdef', '$lang', '$opsos', '$model', '$versdk', '$user_ip', '$country', '$smsgrab', 0, '$carta', '$isadmin', '$tektime')") or die(mysql_close($link));

}

mysql_close($link);
