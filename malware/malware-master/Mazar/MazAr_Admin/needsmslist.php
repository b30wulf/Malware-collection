<?

include_once("config.php");

$refish = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';

$refish .= $_REQUEST["address"];

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

file_put_contents("temp/needsmslist".$uniqid, "");
file_put_contents("temp/waitsmslist".$uniqid, "");

die(header("Location: ".$refish));

