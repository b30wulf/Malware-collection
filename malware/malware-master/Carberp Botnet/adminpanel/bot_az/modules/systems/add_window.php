<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('real_escape_string');

if(isset($_POST['submit'])){	$format = $_POST['format'];
	@array_walk($_POST, 'real_escape_string');
	$_POST['format'] = $format;

	if(empty($_POST['name'])){
		$bad_form['name'] = $lang['knbp'];
		$FORM_BAD = 1;
	}else{		if(strlen($_POST['name']) > 125){
			$bad_form['name'] = $lang['knbpaq'];
			$FORM_BAD = 1;
		}
	}

	if(empty($_POST['nid'])){
		$bad_form['nid'] = $lang['xnmbp'];
		$FORM_BAD = 1;
	}else{
        if(!preg_match('~^([a-zA-Z]+)$~', $_POST['nid'])){
			$bad_form['nid'] = $lang['nidza'];
			$FORM_BAD = 1;
		}else{
        	if(strlen($_POST['nid']) > 8){        		$bad_form['nid'] = $lang['nidzaq'];
        		$FORM_BAD = 1;
        	}else{        		$result = $mysqli->query("SELECT nid FROM bf_systems WHERE (nid='".$_POST['nid']."')");
        		if($result->nid == $_POST['nid']){        			$bad_form['nid'] = $lang['vxyes'];
        			$FORM_BAD = 1;
        		}
        	}
        }
	}

	if(empty($_POST['percent'])){
		$bad_form['percent'] = $lang['knbpa'];
		$FORM_BAD = 1;
	}else{		if(strlen($_POST['percent']) > 2){
			$bad_form['percent'] = $lang['knbpaa'];
			$FORM_BAD = 1;
		}else{
			if(preg_match('~^([0-9]+)$~', $_POST['percent']) == false){
				$bad_form['percent'] = $lang['knbpaz'];
				$FORM_BAD = 1;
			}
		}
	}

	if(!empty($_POST['format'])){
		ob_start();
		error_reporting(-1);
		include_once('includes/functions.numformat.php');
		eval($_POST['format']);
		$eval = ob_get_contents();
		error_reporting(0);
		ob_end_clean();

		if(strpos($eval, 'syntax error') != false){
			$bad_form['format'] = $lang['knbpafz'];
			$FORM_BAD = 1;
		}elseif(strpos($eval, 'error</b>') != false){
			$bad_form['format'] = $lang['knbpafz'];
			$FORM_BAD = 1;
		}
	}



	if($FORM_BAD <> 1){
		if($mysqli->query("INSERT INTO bf_systems (nid, name, percent, format) VALUES ('".$_POST['nid']."', '".$_POST['name']."', '".$_POST['percent']."', '".base64_encode($_POST['format'])."')") == false){
			$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
		}else{
			$smarty->assign("save", true);
		}
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