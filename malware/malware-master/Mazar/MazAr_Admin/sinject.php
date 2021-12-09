<?

include_once("config.php");

$refish = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';

$refish .= $_REQUEST["address"];

if(stripos($refish, $adminphp) === false)
{
die(header("Location: ".$refish));
}

if(isset($_REQUEST["id"])) {
	$uniqum = $_REQUEST["id"];
}
else {
	die(header("Location: ".$refish));
}

if(is_file("temp/needinject".$uniqum)) {
	unlink("temp/needinject".$uniqum);
	file_put_contents("temp/stopinj".$uniqum, "");
} else {
	if(is_file("temp/stopinj".$uniqum)) unlink("temp/stopinj".$uniqum);
	file_put_contents("temp/needinject".$uniqum, "");
}

die(header("Location: ".$refish));
