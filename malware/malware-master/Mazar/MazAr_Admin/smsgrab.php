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

if(is_file("temp/grab".$uniqid)) {
	unlink("temp/grab".$uniqid);
	file_put_contents("temp/stopgrab".$uniqid, "");
} else {
	if(is_file("temp/stopgrab".$uniqid)) unlink("temp/stopgrab".$uniqid);
	file_put_contents("temp/grab".$uniqid, "");
}

die(header("Location: ".$refish));
