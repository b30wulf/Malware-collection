<?

$ip=$_SERVER["REMOTE_ADDR"];
if(isset($_SERVER['HTTP_X_REAL_IP']))
{
if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
{
$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
$ip=$_SERVER['HTTP_X_REAL_IP'];
}
}
if(stripos($ip, ",") !== false)
{
$ip = substr($ip, 0, strpos($ip, ","));
}
$user_ip = sprintf("%u", ip2long($ip));

function cleanDirectory($dir) {
    if ($objs = glob($dir."/*")) {
       foreach($objs as $obj) {
         is_dir($obj) ? removeDirectory($obj) : unlink($obj);
       }
    }
	file_put_contents($dir."/index.html", "<html><head></head><body></body></html>");
}
