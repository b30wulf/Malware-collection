<?php

error_reporting(-1);
ini_set('max_execution_time', 0);
$dir = realpath('.');
$count = 0;
$cou = 0;

function _ago($tm,$rcs = 0) {
    $cur_tm = time(); $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
    return $x;
}

//$type = 'fgr';
$type = 'gra';

$file = scandir($dir . '/logs/unnecessary/'.$type.'/');
unset($file[0], $file[1]);
foreach($file as $f){	$fi =$dir . '/logs/unnecessary/'.$type.'/' . $f;
	$q = $mysqli->query('SELECT id FROM bf_unnecessary WHERE (md5 = \''.$f.'\') LIMIT 1');
	if(empty($q->id)){		//echo $fi . "<br>";
		//unlink($fi);
		$count++;
	}else{		if((time() - filemtime($fi)) > (2678400 * 3)){
			//echo $fi . "<br>";
			unlink($fi);
			$count++;
		}
	}
	$cou++;
}

echo $count . '|' . $cou;

?>