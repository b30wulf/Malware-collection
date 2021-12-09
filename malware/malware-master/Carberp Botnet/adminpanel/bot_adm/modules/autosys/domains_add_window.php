<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('real_escape_string');


if(isset($_POST['submit'])){
	@array_walk($_POST, 'real_escape_string');
	$_POST['domains'] = @strtolower($_POST['domains']);

	if(empty($_POST['domains'])){
		$bad_form['domains'] = $lang['alnbp'];
		$FORM_BAD = 1;
	}

	if($FORM_BAD <> 1){
		$domains = explode("\n", $_POST['domains']);
                
                foreach($domains as $item){
                    $item = trim($item);
                    if(strlen($item) > 0 && strlen($item) <= 128){
                        if(!empty($_POST['check'])){
				$mysqli->query('INSERT INTO `bf_domains` (`host`, `answer`, `post_date`) VALUES (\''.$item.'\', \'1\', CURRENT_TIMESTAMP);');
			}else{
				$mysqli->query('INSERT INTO `bf_domains` (`host`, `answer`, `post_date`) VALUES (\''.$item.'\', \'0\', CURRENT_TIMESTAMP);');
			}
                    }
                }
                print('<script language="javascript" type="application/javascript">window_close(document.getElementById(\'div_sub_'.($smarty->tpl_vars['rand_name']->value).'\').parentNode.id.replace(\'_content\', \'_wid\'), 1); get_hax({url: \'/autosys/domains.html?ajax=1\', id: \'content\'});</script>');
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