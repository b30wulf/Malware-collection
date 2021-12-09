<?php
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');
    if($item->id == $Cur['id'] && empty($item->host)){
	    $item->parent_id = explode('|', $item->parent_id);
	    $parent = $mysqli->query('SELECT * from bf_filters WHERE id = '.$item->parent_id[count($item->parent_id)-2].' LIMIT 1');
        $smarty->assign("parent", $parent);
        $smarty->assign("item", $item);
	    if(isset($_POST['name'])){	    	array_walk($_POST, 'real_escape_string');

	    	if(empty($_POST['name'])){	    		$bad_form['name'] = $lang['fnmbp'];
	    		$FORM_BAD = 1;
	    	}

	    	if($FORM_BAD <> 1){	    		$sql = 'update bf_filters set name = \''.$_POST['name'].'\' WHERE (id = \''.$item->id.'\')';

	    		if($mysqli->query($sql) == false){	    			$errors .= '<div class="t"><div class="t4" align="center">'.$lang['ipsnp'].'</div></div>';
	    		}else{	    			$smarty->assign("save", true);
	    		}
	    	}else{	    		if(count($bad_form) > 0){	    			rsort($bad_form);
	    			for($i = 0; $i < count($bad_form); $i++){	    				if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
	    				$errors .= '<div align="center">' . $bad_form[$i] . '</div>';
	    			}
	    		}
	    	}

	    	$smarty->assign("errors", $errors);

			$dir['1'] = $parent->name;
			$dir['2'] = '<a href="/'.$Cur['to'].'/add_sub-'.$Cur['id'].'.html">'.$dirs['catalog']['add_sub'].'</a>';
		}
	}else{
		exit;
	}
}

?>