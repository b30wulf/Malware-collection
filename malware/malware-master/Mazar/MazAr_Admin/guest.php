<?

if(isset($_GET["id"]) && (strlen(trim($_GET["id"])) == 5)) {
	$prefix = $_GET["id"];
} else {
	exit(0);
}

include_once("config.php");

$link = mysql_connect($mysql_host, $mysql_user, $mysql_pwd);
if (!$link) {
    die();
}

mysql_select_db($mysql_db) || die(mysql_close($link));

$row = mysql_fetch_array(mysql_query("select count(*) as howmany from stata where pefixa='".$prefix."'"));
$number_of_rows = $row["howmany"]; 

$countrys = array();

$q = "SELECT country FROM stata where pefixa='".$prefix."' order by country asc";
$r = mysql_query($q);

if (mysql_num_rows($r) > 0) {
	while ($l = mysql_fetch_array($r)) {
		$countrys[] = $l['country'];
	}
}

mysql_close($link);

echo "Installed: ".$number_of_rows."<br>\r\n";

/**$result = array();

foreach ($countrys as $key => $value) {
	$count = 0;
	foreach ($countrys as $a) {
		if ($a == $countrys[$key]) $count++;
	}
	$result[$key][$value] = $count;
}

$strana = array();

$j = 0;
foreach ($result as $key => $value) {
	if($j != 0) {
		if($result[$j] != $result[$j - 1]) {
			$strana[$j] = $result[$j];
		}
	} else {
			$strana[$j] = $result[$j];
			}
	$j ++;
}

echo count($strana); echo "<br>\r\n";

echo "Country:<br>\r\n";

for ($i = 0; $i < count($strana); $i ++) {
	foreach ($strana[$i] as $key => $value) {
	    print_r($strana[$i]); /**echo " ".$strana[$i][$key]; echo "<br>\r\n";
		
	}
}*/

