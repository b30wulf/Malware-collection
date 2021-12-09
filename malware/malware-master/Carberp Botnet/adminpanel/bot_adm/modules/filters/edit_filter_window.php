<?php
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
$smarty->assign('value_name', 'p' . $smarty->tpl_vars['rand_name']->value);
if(!empty($Cur['id'])){
	$item = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');
	if($item->id == $Cur['id'] && !empty($item->host)){
		$item->fields = json_decode(base64_decode($item->fields), true);
		$item->parent_id = explode('|', $item->parent_id);
	    $parent = $mysqli->query('SELECT * from bf_filters WHERE id = '.$item->parent_id[count($item->parent_id)-2].' LIMIT 1');
        $smarty->assign("parent", $parent);
        $smarty->assign("item", $item);

		if(isset($_POST['submit']) && $_POST['submit'] == $lang['add']){
	        $_POST['name'] = real_escape_string($_POST['name']);
	        $_POST['host'] = real_escape_string($_POST['host']);
	        $_POST['savelog'] = real_escape_string($_POST['savelog']);

            if($_POST['savelog'] == 'on'){            	$_POST['savelog'] = '1';
            }else{            	$_POST['savelog'] = '0';
            }

			if(empty($_POST['name'])){
				$bad_form['name'] = $lang['fnmbp'];
				$FORM_BAD = 1;
			}

			if(empty($_POST['host'])){
				$bad_form['host'] = $lang['snmbp'];
				$FORM_BAD = 1;
			}else{
				if($item->host != $_POST['host']){
					if($mysqli->query_name('SELECT host from bf_filters WHERE host = \''.$_POST['host'].'\' LIMIT 1', null, 'host') == $_POST['host']){
						$bad_form['host'] = $lang['dsyes'];
						$FORM_BAD = 1;
					}
				}
			}

			if(preg_match('~^([a-zA-Z0-9.,-]+)$~', $_POST['host']) != true){
				$bad_form['host_words'] = $lang['smststzrd'];
				$FORM_BAD = 1;
			}

			if($FORM_BAD <> 1){
	            if($mysqli->query('update bf_filters set name = \''.$_POST['name'].'\', host = \''.$_POST['host'].'\' WHERE (id = \''.$item->id.'\')') == false){
					$errors .= '<div class="t"><div class="t4" align="center">'.$lang['sfsnp'].'</div></div>';
				}else{
					 $smarty->assign("save", true);
				}
			}else{
				if(count($bad_form) > 0){
					rsort($bad_form);
					for($i = 0; $i < count($bad_form); $i++){
						if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
						$errors .= '<div align="center">' . $bad_form[$i] . '</div>';
					}
				}
			}
			$smarty->assign("errors", $errors);
		}else{			$_POST['name'] = $item->name;
			$_POST['host'] = $item->host;
			//$_POST['savelog'] = $item->save_log;
		}

		$dir['1'] = $parent->name;
		$dir['2'] = '<a href="/'.$Cur['to'].'/edit_filter-'.$Cur['id'].'.html">'.$dirs['catalog']['edit_filter'].'</a>';
	}
}

?>